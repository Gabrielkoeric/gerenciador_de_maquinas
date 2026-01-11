<?php

namespace App\Repositories\LogSql;

use Illuminate\Support\Facades\DB;
use App\Services\SqlMonitorService;

class LogSqlRepository
{
    public function listarCompleto()
    {
        SqlMonitorService::start(2, 1);
/*
        return DB::table('logs_sql as logs_sql')
            ->select('*')
            ->orderBy('id_log_sql', 'desc')
            ->get();

*/
    return DB::table('logs_sql as log')
        ->select([
            'log.*',
            'rotina.nome as nome_rotina',
            'acao.nome as nome_acao',
            'usuario.nome_completo as usuario_nome',
        ])
        ->leftJoin('rotinas as rotina', 'rotina.id_rotina', '=', 'log.id_rotina')
        ->leftJoin('acoes as acao', 'acao.id_acao', '=', 'log.id_acao')
        ->leftJoin('usuarios as usuario', 'usuario.id', '=', 'log.id')
        ->orderBy('log.id_log_sql', 'desc')
        ->get();

    }

    public function inserir(array $dados): void
    {
        DB::table('logs_sql')->insert($dados);
    }

    public function clear(): void
    {
        DB::table('logs_sql')->truncate();
    }
}

