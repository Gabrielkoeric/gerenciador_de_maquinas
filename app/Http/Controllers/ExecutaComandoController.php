<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\BuscaVm;
use App\Jobs\Server\StatusServer;
use App\Jobs\Server\StartServer;
use App\Jobs\Server\StopServer;
use App\Jobs\Server\RestartServer;
use App\Jobs\Server\ListaVmServer;
use App\Jobs\Server\InsereVmServer;
use App\Jobs\ManipulaServicoWindows;
use Carbon\Carbon;

class ExecutaComandoController extends Controller
{
    public function manipulaHostFisico(Request $request) {
        
        $servers = $request->input('server');
        $acao = $request->input('acao');

        foreach ($servers as $server) {
            $dados = DB::table('servidor_fisico as sf')
                ->leftJoin('dominio as d', 'sf.id_dominio', '=', 'd.id_dominio')
                ->leftJoin('usuario_servidor_fisico as usf', function ($join) {
                    $join->on('usf.id_servidor_fisico', '=', 'sf.id_servidor_fisico')
                    ->where('usf.principal', '=', 1);
                })
                ->leftJoin('ip_lan as il', 'sf.id_ip_lan', '=', 'il.id_ip_lan')
                ->leftJoin('ip_wan as iw', 'sf.id_ip_wan', '=', 'iw.id_ip_wan')
                ->select(
                    'sf.*',
                    'd.nome as dominio_nome',
                    'd.usuario as dominio_usuario',
                    'd.senha as dominio_senha',
                    'usf.usuario as usuario_servidor',
                    'usf.senha as senha_servidor',
                    'il.ip as ip_lan',
                    'iw.ip as ip_wan'
                )
                ->where('sf.id_servidor_fisico', $server)
                ->first();

                switch ($acao) {
                    case 'status':
                        $taskId = DB::table('async_tasks')->insertGetId([
                            'nome_async_tasks' => 'StatusServer',
                            'horario_disparo' => Carbon::now(),
                            'parametros' => json_encode($dados),
                            'status' => 'Pendente',
                        ]);
                        StatusServer::dispatch($dados, $taskId);
                        break;
                    case 'start':
                        $taskId = DB::table('async_tasks')->insertGetId([
                            'nome_async_tasks' => 'StartServer',
                            'horario_disparo' => Carbon::now(),
                            'parametros' => json_encode($dados),
                            'status' => 'Pendente',
                        ]);
                        StartServer::dispatch($dados, $taskId);
                        break;
                    case 'stop':
                        $taskId = DB::table('async_tasks')->insertGetId([
                            'nome_async_tasks' => 'StopServer',
                            'horario_disparo' => Carbon::now(),
                            'parametros' => json_encode($dados),
                            'status' => 'Pendente',
                        ]);
                        StopServer::dispatch($dados, $taskId);
                        break;
                    case 'restart':
                        $taskId = DB::table('async_tasks')->insertGetId([
                            'nome_async_tasks' => 'RestartServer',
                            'horario_disparo' => Carbon::now(),
                            'parametros' => json_encode($dados),
                            'status' => 'Pendente',
                        ]);
                        RestartServer::dispatch($dados, $taskId);
                        break;
                    case 'listaVm':
                        $taskId = DB::table('async_tasks')->insertGetId([
                            'nome_async_tasks' => 'ListaVmServer',
                            'horario_disparo' => Carbon::now(),
                            'parametros' => json_encode($dados),
                            'status' => 'Pendente',
                        ]);
                        ListaVmServer::dispatch($dados, $taskId);
                        break;
                    case 'insereVM':
                        $taskId = DB::table('async_tasks')->insertGetId([
                            'nome_async_tasks' => 'InsereVmServer',
                            'horario_disparo' => Carbon::now(),
                            'parametros' => json_encode($dados),
                            'status' => 'Pendente',
                        ]);
                        InsereVmServer::dispatch($dados, $taskId);
                        break;
                }    
        }
        return redirect('/server');
    }
    public function executarComando(Request $request)
    {
        $servidores = DB::table('servidor_fisico as s')
        ->join('usuario_servidor_fisico as u', function ($join) {
        $join->on('s.id_servidor_fisico', '=', 'u.id_servidor_fisico')
             ->where('u.principal', 1);
        })
        ->leftJoin('ip_lan as i', 's.id_ip_lan', '=', 'i.id_ip_lan')
        ->where('s.tipo', 'rdp')
        ->select('s.*', 'u.usuario', 'u.senha', 'i.ip as iplan')
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
                ->join('usuario_vm', function ($join) {
                    $join->on('usuario_vm.id_vm', '=', 'vm.id_vm')
                ->where('usuario_vm.principal', 1); // apenas usuário principal
            })
            ->leftJoin('ip_lan', 'vm.id_ip_lan', '=', 'ip_lan.id_ip_lan') // relacionamento com IP
            ->where('servico_vm.id_servico_vm', $id_servico_vm)
            ->select(
                'ip_lan.ip as iplan',
                'vm.dominio', 
                'vm.so', 
                'usuario_vm.usuario', 
                'usuario_vm.senha', 
                'servico_vm.nome'
            )
            ->first(); // Como você deseja uma linha de informação, usamos `first()` para pegar apenas o primeiro resultado   
            if ($dados->so === 'rdp') {
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
                $usuarioLogado = auth()->id(); 
                Log::info("usuario logado $usuarioLogado");
                
                
                ManipulaServicoWindows::dispatch($dados->iplan, $dados->usuario, $dados->senha, $dados->dominio, $dados->nome, $acao, $taskId, $id_servico_vm, $usuarioLogado);
            }
        }
        return redirect('/vm_servico');
    }

    public function manipulaVm (Request $request)
    {
        dd($request);
    }
}