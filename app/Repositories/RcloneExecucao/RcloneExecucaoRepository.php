<?php

namespace App\Repositories\RcloneExecucao;


use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RcloneExecucaoRepository
{
    public function criarExecucaoDiaria(int $idRepositorio): int
    {
        return DB::table('rclone_execucoes')->insertGetId([
            'id_repositorio' => $idRepositorio,
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
}