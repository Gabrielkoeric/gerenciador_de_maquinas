<?php

namespace App\Repositories\LogSql;

use Illuminate\Support\Facades\DB;
use App\Services\SqlMonitorService;

class LogSqlRepository
{
    public function listarCompleto()
    {
        SqlMonitorService::start(2, 1);

        return DB::table('logs_sql as logs_sql')
            ->select('*')
            ->orderBy('id_log_sql', 'desc')
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

