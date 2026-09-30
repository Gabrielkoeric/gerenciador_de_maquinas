<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Jobs\ManipulaServicoWindows;

class ReiniciaWS extends Command
{
    protected $signature = 'reinicia:ws';

    protected $description = 'Reinicia os serviços WS';

    public function handle()
    {
        $servicos = DB::table('servico_vm as sv')
            ->join('vm as v', 'sv.id_vm', '=', 'v.id_vm')
            ->join('ip_lan as ip', 'v.id_ip_lan', '=', 'ip.id_ip_lan')
            ->leftJoin('dominio as d', 'v.id_dominio', '=', 'd.id_dominio')
            ->leftJoin('usuario_vm as u', function ($join) {
                $join->on('v.id_vm', '=', 'u.id_vm')
                    ->where('u.principal', '=', 1);
            })
            ->where('sv.id_servico', 8)
            ->select(
                'sv.*',
                'v.nome as vm_nome',
                'v.id_ip_lan',
                'v.id_dominio',
                'v.so',
                'ip.ip as ip_lan',
                'd.nome as dominio_nome',
                'd.usuario as dominio_usuario',
                'd.senha as dominio_senha',
                'u.usuario as usuario_local',
                'u.senha as senha_local'
            )
            ->get();

        foreach ($servicos as $dados) {

            if ($dados->so === 'rdp') {

                $parametros = [
                    'iplan' => $dados->ip_lan,
                    'usuario_local' => $dados->usuario_local,
                    'senha_local' => $dados->senha_local,
                    'dominio_usuario' => $dados->dominio_usuario ?? null,
                    'dominio_senha' => $dados->dominio_senha ?? null,
                    'dominio' => $dados->dominio_nome ?? null,
                    'servico' => $dados->nome,
                    'acao' => 'restart',
                ];

                $taskId = DB::table('async_tasks')->insertGetId([
                    'nome_async_tasks' => 'ManipulaServicoWindows',
                    'horario_disparo' => Carbon::now(),
                    'parametros' => json_encode($parametros),
                    'status' => 'Pendente',
                ]);

                $usuarioSystem = 100;

                Log::info('ReiniciaWS - serviço encontrado', [
                    'ip_lan' => $dados->ip_lan,
                    'servico' => $dados->nome,
                    'vm' => $dados->vm_nome
                ]);

                
                ManipulaServicoWindows::dispatch(
                    $dados,
                    'restart',
                    $taskId,
                    $usuarioSystem
                );
            }
        }
    }
}