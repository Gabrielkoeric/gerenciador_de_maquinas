<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Relatorio;
use Exception;

class ProcessRelatorioIpEmail2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $nome;
    protected $id_incidente;
    protected $email;
    protected $id_async_task;

    public function __construct($nome, $id_incidente, $email, $id_async_task)
    {
        $this->nome = $nome;
        $this->id_incidente = $id_incidente;
        $this->email = $email;
        $this->id_async_task = $id_async_task;
    }

    public function handle()
    {
        Log::info("email assinc $this->email");

        DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
            'status' => 'iniciado',
            'horario_inicio' => now()
        ]);

        $relatorioIp = collect(); // Inicializa a coleção para armazenar os dados processados

        DB::table('ip')
            ->select(
                'ip.id_ip',
                'ip.ip',
                'ip.cidade',
                'ip.regiao',
                'ip.continente',
                'ip.localizacao',
                'ip.empresa',
                'ip.postal',
                'ip.timezone',
                'ip_incidente.quantidade'
            )
            ->join('ip_incidente', 'ip.id_ip', '=', 'ip_incidente.id_ip')
            ->join('incidente', 'ip_incidente.id_incidente', '=', 'incidente.id_incidente')
            ->where('incidente.id_incidente', $this->id_incidente)
            ->orderByDesc('ip_incidente.quantidade') // Ordenar por quantidade em ordem decrescente
            ->orderBy('ip.empresa', 'asc') // Ordenar por empresa em ordem crescente
            ->orderBy('ip.ip', 'asc') // Ordenar por IP em ordem crescente
            ->chunk(1000, function ($ips) use (&$relatorioIp) {
                $relatorioIp = $relatorioIp->merge($ips); // Mescla os chunks na coleção
            });

        $nomeArquivo = $this->nome . '.pdf';
        $caminhoArquivo = storage_path('app/public/arquivosIpPDF/' . $nomeArquivo);

        // Carrega a view do layout para gerar o conteúdo do PDF
        $pdf = PDF::loadView('ip.relatorioip', ['relatorioIp' => $relatorioIp]);

        // Salva o arquivo PDF no diretório específico com o nome do arquivo
        $pdf->save($caminhoArquivo);

        DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
            'status' => 'concluido',
            'horario_fim' => now(),
            'log' => "Arquivo PDF processado, salvo no caminho $caminhoArquivo"
        ]);

        Mail::to($this->email)->queue(new Relatorio($nomeArquivo, $caminhoArquivo));
    }
}
