<?php

namespace App\Jobs;


use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Relatorio;

class ProcessRelatorioIpEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("email assinc $this->email");
        DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
            'status' => 'iniciado',
            'horario_inicio' => now()
        ]);
        $relatorioIp = DB::table('ip')
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
            ->where('incidente.id_incidente', $this->id_incidente) // Corrigido aqui
            ->get();

        $nomeArquivo = $this->nome . '.pdf'; // Corrigido aqui
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
        //Mail::to($this->email)->queue(new Relatorio($caminhoArquivo));
        Mail::to($this->email)->queue(new Relatorio($nomeArquivo, $caminhoArquivo));

    }
}
