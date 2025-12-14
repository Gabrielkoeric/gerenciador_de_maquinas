<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Jobs\Rclone\RcloneJobReplicacao;
use Illuminate\Support\Facades\Log;

class BkpAnexoReplicacao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bkp:replicacao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Comando bkp:replicacao executado!');

        // Busca os repositórios ativos do tipo "replicacao" ordenando pela prioridade
        $repositorios = DB::table('repositorios')
        ->where('diario', 1)
        ->where('tipo', 'replicacao')
        ->orderBy('prioridade', 'asc')
        ->get();

        // Insere as execuções na ordem correta
    
        foreach ($repositorios as $repositorio) {

            $id_execucao = DB::table('rclone_execucoes')->insertGetId([
                'id_repositorio' => $repositorio->id_repositorios,
                'tipo' => 'replicacao',
                'status' => 'pendente',
                'disparo' => Carbon::now(),
                'inicio' => null,
                'fim' => null,
                'qtd_arquivos_transferidos' => null,
                'qtd_arquivos_check' => null,
                'bytes_transferidos' => null,
                'log_path' => null,
                'erro' => null,
            ]);

            RcloneJobReplicacao::dispatch($id_execucao)->onQueue('replicacao');
        }

    }
}
