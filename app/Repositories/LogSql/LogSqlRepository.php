<?php

namespace App\Repositories\LogSql;

use Illuminate\Support\Facades\DB;

class LogSqlRepository
{
    public function inserir(array $dados): void
    {
        DB::table('logs_sql')->insert($dados);
    }
}

