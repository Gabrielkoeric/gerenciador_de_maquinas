<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Jobs\Rclone\RcloneJobDiario;

class BkpAnexoDiario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bkp:diario';

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
        // Busca os repositórios ativos do tipo "arquivo" ordenando pela prioridade
    $repositorios = DB::table('repositorios')
        ->where('diario', 1)
        ->where('tipo', 'arquivo')
        ->orderBy('prioridade', 'asc')
        ->get();

    // Insere as execuções na ordem correta
    foreach ($repositorios as $repositorio) {
        DB::table('rclone_execucoes')->insert([
            'id_repositorio' => $repositorio->id_repositorios,
            'tipo' => 'diario',
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
    }

    $execucoesPendentes = DB::table('rclone_execucoes')
        ->where('status', 'pendente')
        ->where('tipo', 'diario')
        ->orderBy('id_execucao')
        ->limit(1)
        ->get();

    foreach ($execucoesPendentes as $execucao) {
    RcloneJobDiario::dispatch($execucao->id_execucao)->onQueue('diario');
    }
    }
}
