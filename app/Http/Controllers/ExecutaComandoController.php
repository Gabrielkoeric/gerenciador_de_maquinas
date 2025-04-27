<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\BuscaVm;
use App\Jobs\ManipulaServicoWindows;
use Carbon\Carbon;

class ExecutaComandoController extends Controller
{
    public function executarComando(Request $request)
    {
        $servidores = DB::table('servidor_fisico as s')
                    ->join('usuario_servidor_fisico as u', 's.id_servidor_fisico', '=', 'u.id_servidor_fisico')
                    ->where('s.tipo', 'rdp')
                    ->select('s.*', 'u.usuario', 'u.senha')
                    ->get();

        foreach ($servidores as $server) {
            // Define os parâmetros que quer armazenar (em JSON)
            $parametros = [
                'iplan' => $server->iplan,
                'usuario' => $server->usuario,
                'dominio' => $server->dominio ?? null, // caso não exista domínio
            ];

            // Cria o registro do task no banco
            $taskId = DB::table('async_tasks')->insertGetId([
                'nome_async_tasks' => 'BuscaVm',
                'horario_disparo' => Carbon::now(),
                'parametros' => json_encode($parametros),
                'status' => 'Pendente',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    
            // Dispara o Job passando o servidor e o ID da task
            //BuscaVm::dispatch($server, $taskId);
            BuscaVm::dispatch($server->iplan, $server->usuario, $server->senha, $server->dominio, $taskId);

        }
        return redirect('/server')->with('success', 'Comando executado com sucesso!');
    }

    public function manipulaServico (Request $request)
    {
        //dd($request);
        $servicos = $request->input('servicos');
        $acao = $request->input('acao');
        foreach ($servicos as $id_servico_vm) {
            $dados = DB::table('servico_vm')
                ->join('vm', 'servico_vm.id_vm', '=', 'vm.id_vm')
                ->join('usuario_vm', 'usuario_vm.id_vm', '=', 'vm.id_vm')
                ->where('servico_vm.id_servico_vm', $id_servico_vm) // Filtrando pelo id_servico_vm específico
                ->select(
                    'vm.iplan', 
                    'vm.dominio', 
                    'vm.tipo', 
                    'usuario_vm.usuario', 
                    'usuario_vm.senha', 
                    'servico_vm.nome'
                )
                ->first(); // Como você deseja uma linha de informação, usamos `first()` para pegar apenas o primeiro resultado   
            if ($dados->tipo === 'rdp') {
                $parametros = [
                    'iplan' => $dados->iplan,
                    'usuario' => $dados->usuario,
                    'dominio' => $dados->dominio ?? null,
                    'servico' => $dados->nome,
                    'acao' => $acao,
                ];

                $taskId = DB::table('async_tasks')->insertGetId([
                    'nome_async_tasks' => 'ManipulaServicoWindows',
                    'horario_disparo' => Carbon::now(),
                    'parametros' => json_encode($parametros),
                    'status' => 'Pendente',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]); 
                
                ManipulaServicoWindows::dispatch($dados->iplan, $dados->usuario, $dados->senha, $dados->dominio, $dados->nome, $acao, $taskId);
            }
        }
        return redirect('/vm_servico');
    }
}
