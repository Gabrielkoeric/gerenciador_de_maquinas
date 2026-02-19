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

    public function marcarComoIniciado(int $taskId): int
    {
        return DB::table('async_tasks')
            ->where('id_async_tasks', $taskId)
            ->update([
                'horario_inicio' => Carbon::now(),
                'status'         => 'Iniciado'
            ]);
    }

    public function marcarComoConcluido(int $taskId, string $log, string $comando): void 
    {
        $task = DB::table('async_tasks')
            ->where('id_async_tasks', $taskId)
            ->value('parametros');

        $parametros = [];

        $parametros = json_decode($task, true) ?? [];

        $parametros['comando'] = $comando;

        DB::table('async_tasks')
            ->where('id_async_tasks', $taskId)
            ->update([
                'horario_fim' => now(),
                'status'      => 'Concluido',
                'log'         => $log,
                'parametros'  => json_encode($parametros),
            ]);
}

}