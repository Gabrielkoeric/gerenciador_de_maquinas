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
use App\Jobs\Server\RealocaVmServer;
use App\Jobs\Vm\StatusVm;
use App\Jobs\Vm\StartVm;
use App\Jobs\Vm\StopVm;
use App\Jobs\Vm\RestartVm;
use App\Jobs\ManipulaServicoWindows;
use Carbon\Carbon;

class ExecutaComandoController extends Controller
{
    public function manipulaHostFisico(Request $request) {
        //dd($request);
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
                    case 'realocaVm':
                        $taskId = DB::table('async_tasks')->insertGetId([
                            'nome_async_tasks' => 'RealocaVmServer',
                            'horario_disparo' => Carbon::now(),
                            'parametros' => json_encode($dados),
                            'status' => 'Pendente',
                        ]);
                        RealocaVmServer::dispatch($dados, $taskId);
                        break;
                }    
        }
        return redirect('/server');
    }

    public function manipulaVm (Request $request)
    {
        $vms = $request->input('vm');
        $acao = $request->input('acao');
       
        foreach ($vms as $vm) {

        $dados = DB::table('vm')
            ->select(
                'vm.*',
                'ip_lan.ip as ip_lan_vm',
                'dominio.nome as dominio_nome',
                'dominio.usuario as dominio_usuario',
                'dominio.senha as dominio_senha',
                'servidor_fisico.nome as nome_servidor_fisico',
                'usuario_vm.usuario as usuario_local',
                'usuario_vm.senha as senha_local'
            )
            ->leftJoin('ip_lan', 'vm.id_ip_lan', '=', 'ip_lan.id_ip_lan')
            ->leftJoin('dominio', 'vm.id_dominio', '=', 'dominio.id_dominio')
            ->leftJoin('servidor_fisico', 'vm.id_servidor_fisico', '=', 'servidor_fisico.id_servidor_fisico')
            ->leftJoin('usuario_vm', function ($join) {
                $join->on('vm.id_vm', '=', 'usuario_vm.id_vm')
                 ->where('usuario_vm.principal', '=', 1);
            })
            ->where('vm.id_vm', '=', $vm)
            ->first();

            switch ($acao) {
                case 'status':
                    $taskId = DB::table('async_tasks')->insertGetId([
                        'nome_async_tasks' => 'StatusVm',
                        'horario_disparo' => Carbon::now(),
                        'parametros' => json_encode($dados),
                        'status' => 'Pendente',
                    ]);
                    StatusVm::dispatch($dados, $taskId);
                    break;
                case 'start':
                    $taskId = DB::table('async_tasks')->insertGetId([
                        'nome_async_tasks' => 'StartVm',
                        'horario_disparo' => Carbon::now(),
                        'parametros' => json_encode($dados),
                        'status' => 'Pendente',
                    ]);
                    StartVm::dispatch($dados->id_servidor_fisico, $taskId, $dados->nome);
                    break;
                case 'stop':
                    $taskId = DB::table('async_tasks')->insertGetId([
                        'nome_async_tasks' => 'StopVm',
                        'horario_disparo' => Carbon::now(),
                        'parametros' => json_encode($dados),
                        'status' => 'Pendente',
                    ]);
                    StopVm::dispatch($dados, $taskId);
                    break;
                case 'restart':
                    $taskId = DB::table('async_tasks')->insertGetId([
                        'nome_async_tasks' => 'RestartVm',
                        'horario_disparo' => Carbon::now(),
                        'parametros' => json_encode($dados),
                        'status' => 'Pendente',
                    ]);
                    RestartVm::dispatch($dados, $taskId);
                    break;
            }
        }
        return redirect('/vm');    
    }
    /*
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
    */
    public function manipulaServico (Request $request)
    {
        //dd($request);
        $servicos = $request->input('servicos');
        $acao = $request->input('acao');
        if ($acao !== 'kill') {
            foreach ($servicos as $id_servico_vm) {
                $dados = DB::table('servico_vm as sv')
                ->join('vm as v', 'sv.id_vm', '=', 'v.id_vm')
                ->join('ip_lan as ip', 'v.id_ip_lan', '=', 'ip.id_ip_lan')
                ->leftJoin('dominio as d', 'v.id_dominio', '=', 'd.id_dominio')
                ->leftJoin('usuario_vm as u', function ($join) {
                    $join->on('v.id_vm', '=', 'u.id_vm')
                        ->where('u.principal', '=', 1);
                })
                ->where('sv.id_servico_vm', $id_servico_vm)
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
                if ($dados->so === 'rdp') {
                    $parametros = [
                        'iplan' => $dados->ip_lan,
                        'usuario_local' => $dados->usuario_local,
                        'senha_local' => $dados->senha_local,
                        'dominio_usuario' => $dados->dominio_usuario ?? null,
                        'dominio_senha' => $dados->dominio_senha ?? null,
                        'dominio' => $dados->dominio_nome ?? null,
                        'servico' => $dados->nome,
                        'acao' => $acao,
                    ];

                    $taskId = DB::table('async_tasks')->insertGetId([
                        'nome_async_tasks' => 'ManipulaServicoWindows',
                        'horario_disparo' => Carbon::now(),
                        'parametros' => json_encode($parametros),
                        'status' => 'Pendente',
                    ]);
                    $usuarioLogado = auth()->id(); 
                    Log::info("usuario logado $usuarioLogado");
                
                    ManipulaServicoWindows::dispatch($dados, $acao, $taskId, $usuarioLogado);
                }
            }
        }else {
            $vms = DB::table('servico_vm as sv')
            ->join('vm as v', 'sv.id_vm', '=', 'v.id_vm')
            ->join('ip_lan as ip', 'v.id_ip_lan', '=', 'ip.id_ip_lan')
            ->leftJoin('dominio as d', 'v.id_dominio', '=', 'd.id_dominio')
            ->leftJoin('usuario_vm as u', function ($join) {
                $join->on('v.id_vm', '=', 'u.id_vm')
                    ->where('u.principal', '=', 1);
            })
            ->whereIn('sv.id_servico_vm', $servicos)
            ->select(
                'v.id_vm',
                'v.nome as vm_nome',
                'v.so',
                'ip.ip as ip_lan',
                'd.nome as dominio_nome',
                'd.usuario as dominio_usuario',
                'd.senha as dominio_senha',
                'u.usuario as usuario_local',
                'u.senha as senha_local'
            )
            ->distinct() // 👈 evita duplicar VMs
            ->get();

            foreach ($vms as $dados) {
                if ($dados->so === 'rdp') {
                    $parametros = [
                        'iplan' => $dados->ip_lan,
                        'usuario_local' => $dados->usuario_local,
                        'senha_local' => $dados->senha_local,
                        'dominio_usuario' => $dados->dominio_usuario ?? null,
                        'dominio_senha' => $dados->dominio_senha ?? null,
                        'dominio' => $dados->dominio_nome ?? null,
                        'acao' => 'kill',
                    ];

                         $taskId = DB::table('async_tasks')->insertGetId([
                            'nome_async_tasks' => 'ManipulaServicoWindows',
                            'horario_disparo' => Carbon::now(),
                            'parametros' => json_encode($parametros),
                            'status' => 'Pendente',
                        ]);
                        $usuarioLogado = auth()->id(); 
                        $acao = 'kill';
                        ManipulaServicoWindows::dispatch($dados, $acao, $taskId, $usuarioLogado);
                        
                    }
                }
            }     
        
        return redirect('/vm_servico');
    }
}