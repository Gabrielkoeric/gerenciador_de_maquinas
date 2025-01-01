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

class backup_ProcessRelatorioIp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $nome;
    protected $id_incidente;

    public function __construct($nome, $id_incidente)
    {
        $this->nome = $nome;
        $this->id_incidente = $id_incidente;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
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
    }
}
