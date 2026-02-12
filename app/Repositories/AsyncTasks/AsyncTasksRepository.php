<?php

namespace App\Repositories\AsyncTasks;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsyncTasksRepository
{
    public function create(string $nome, array $parametros, string $status = 'Pendente'): int
    {
        return DB::table('async_tasks')->insertGetId([
            'nome_async_tasks' => $nome,
            'horario_disparo'  => Carbon::now(),
            'parametros'       => json_encode($parametros),
            'status'           => $status,
        ]);
    }
}