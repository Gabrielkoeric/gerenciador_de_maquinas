<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusServicoController extends Controller
{
    public function store(Request $request)
{
    $retorno = $request->validate([
        'servicos' => 'required|array',
        'servicos.*.servico' => 'required|string',
        'servicos.*.status' => 'required|string',
    ]);

    $servicosRecebidos = [];

    foreach ($retorno['servicos'] as $item) {

        $nomeServico = $item['servico'];

        $dados = DB::table('servico_vm as sv')
            ->join('vm as v', 'sv.id_vm', '=', 'v.id_vm')
            ->join('ip_lan as ip', 'v.id_ip_lan', '=', 'ip.id_ip_lan')
            ->leftJoin('dominio as d', 'v.id_dominio', '=', 'd.id_dominio')
            ->leftJoin('usuario_vm as u', function ($join) {
                $join->on('v.id_vm', '=', 'u.id_vm')
                     ->where('u.principal', '=', 1);
            })
            ->where('sv.nome', $nome)
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
            ->first();

            $acao = 'start';
            $taskId = DB::table('async_tasks')->insertGetId([
                'nome_async_tasks' => 'ManipulaServicoWindows',
                'horario_disparo' => Carbon::now(),
                'parametros' => json_encode($dados),
                'status' => 'Pendente',
            ]);
            $usuarioLogado = 'system';

            if ($dados->autstart == 1) {
                ManipulaServicoWindows::dispatch($dados, $acao, $taskId, $usuarioLogado);
            }
    }

    return response()->json([
        'message' => 'Status recebido com sucesso.',
        'dados' => $dados,
    ]);
}

}
