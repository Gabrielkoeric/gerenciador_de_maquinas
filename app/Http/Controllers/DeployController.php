<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\Deploy\Swarm;
use App\Jobs\Deploy\Server;
use App\Jobs\Deploy\Ws;
use Carbon\Carbon;

class DeployController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('deploy.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    switch ($id) {
        case 'EscalaServer':
            // Retornar view específica
            $ultimaPorta = DB::table('servico_vm as sv')
                ->join('servico as s', 'sv.id_servico', '=', 's.id_servico')
                ->where('s.nome', 'EscalaServer') // Substitua pelo nome desejado
                ->orderByRaw('CAST(sv.porta AS UNSIGNED) DESC')
                ->limit(1)
                ->value('sv.porta');

            $ultimaPorta = (int) $ultimaPorta ?: 2000;

            $ultimaPorta = $ultimaPorta + 1;

            $vms = DB::table('vm')
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
            ->where('vm.tipo', 'escalaserver')
            ->get();

            $clientes = DB::table('cliente_escala')
                ->whereNotNull('apelido')
                ->where('apelido', '!=', '')
                ->get();

            return view('deploy.server')->with('ultimaPorta', $ultimaPorta)->with('vms', $vms)->with('clientes', $clientes);
            //dd("Escala server");
        case 'EscalaSwarm':
            $vms = DB::table('vm')
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
            ->where('vm.tipo', 'escalaswarm')
            ->orderBy('vm.nome', 'asc')
            ->get();

            $clientes = DB::table('cliente_escala')
                ->whereNotNull('apelido')
                ->where('apelido', '!=', '')
                ->get();

            return view('deploy.swarm')->with('vms', $vms)->with('clientes', $clientes);
        
        case 'EscalaWebService':
            $vms = DB::table('vm')
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
            ->where('vm.tipo', 'escalawebswervice')
            ->orderBy('vm.nome', 'asc')
            ->get();

            $clientes = DB::table('cliente_escala')
                ->whereNotNull('apelido')
                ->where('apelido', '!=', '')
                ->get();

            return view('deploy.ws')->with('vms', $vms)->with('clientes', $clientes);
        
        case 'EscalaWeb':
            //return view('deploy.web');
            dd("escala web");
    }
}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function server(Request $request) {
        
        $ultimaPorta = $request->input('ultimaPorta');
        $cliente = $request->input('cliente');
        $vm = $request->input('vm');

        $clienteDados = DB::table('cliente_escala')
            ->select('id_cliente_escala', 'apelido')
            ->where('id_cliente_escala', $cliente)
            ->first();


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

        $dado = [
            'ultimaPorta' => $ultimaPorta,
            'cliente' => $cliente,
            'vm' => $vm,
        ];

        $idServico = DB::table('servico')
            ->where('nome', 'EscalaServer') 
            ->value('id_servico');

        $taskId = DB::table('async_tasks')->insertGetId([
            'nome_async_tasks' => 'DeployServer',
            'horario_disparo' => Carbon::now(),
            'parametros' => json_encode($dado),
            'status' => 'Pendente',
        ]);
    
        Server::dispatch($ultimaPorta, $clienteDados, $vm, $taskId, $dados, $idServico);

        return redirect('/deploy');
    }

    public function swarm(Request $request) {
        $cliente = $request->input('cliente');
        $vm = $request->input('vm');
        $nome_servico = $request->input('nome');

    //dd("cliente $cliente, vm $vm, nome_servico $nome_servico");

        $vm_aplicacao = DB::table('servico_vm')
            ->select('id_vm', 'porta')
            ->where('id_cliente_escala', $cliente)
            ->where('nome', 'like', '%server%')
            ->limit(1)
            ->first();
        
        $nome_vm_aplicacao = DB::table('vm')
    ->where('id_vm', $vm_aplicacao->id_vm)
    ->value('nome');


        $clienteDados = DB::table('cliente_escala')
            ->select('id_cliente_escala', 'apelido')
            ->where('id_cliente_escala', $cliente)
            ->first();
            
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

            $dado = [
            'nome_servico' => $nome_servico,
            'cliente' => $cliente,
            'vm_swarm' => $vm,
            'id_vm_aplicacao' => $vm_aplicacao->id_vm,
            'porta' => $vm_aplicacao->porta,
            'nome_vm_aplicacao' => $nome_vm_aplicacao,
            'clienteDados' => $clienteDados->apelido,
        ];

            $taskId = DB::table('async_tasks')->insertGetId([
            'nome_async_tasks' => 'DeploySwarm',
            'horario_disparo' => Carbon::now(),
            'parametros' => json_encode($dado),
            'status' => 'Pendente',
        ]);
        //Swarm::dispatch($vm_aplicacao->porta, $nome_vm_aplicacao, $clienteDados, $dados, $nome_servico, $taskId);
        Swarm::dispatch($clienteDados, $dados, $vm_aplicacao, $nome_vm_aplicacao, $nome_servico, $taskId);

        //Swarm::dispatch($clienteDados['apelido'], $dados, $vm_aplicacao['porta'], $nome_vm_aplicacao, $taskId);
        return redirect('/deploy');
    }

    public function ws(Request $request) {
        $cliente = $request->input('cliente');
        $vm = $request->input('vm');
        $nome_servico = $request->input('nome');

    //dd("cliente $cliente, vm $vm, nome_servico $nome_servico");

        $vm_aplicacao = DB::table('servico_vm')
            ->select('id_vm', 'porta')
            ->where('id_cliente_escala', $cliente)
            ->where('nome', 'like', '%server%')
            ->limit(1)
            ->first();
        
        $nome_vm_aplicacao = DB::table('vm')
            ->where('id_vm', $vm_aplicacao->id_vm)
            ->value('nome');


        $clienteDados = DB::table('cliente_escala')
            ->select('id_cliente_escala', 'apelido')
            ->where('id_cliente_escala', $cliente)
            ->first();
            
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

            $dado = [
            'nome_servico' => $nome_servico,
            'cliente' => $cliente,
            'vm_swarm' => $vm,
            'id_vm_aplicacao' => $vm_aplicacao->id_vm,
            'porta' => $vm_aplicacao->porta,
            'nome_vm_aplicacao' => $nome_vm_aplicacao,
            'clienteDados' => $clienteDados->apelido,
        ];

            $taskId = DB::table('async_tasks')->insertGetId([
            'nome_async_tasks' => 'DeployWS',
            'horario_disparo' => Carbon::now(),
            'parametros' => json_encode($dado),
            'status' => 'Pendente',
        ]);
        //Swarm::dispatch($vm_aplicacao->porta, $nome_vm_aplicacao, $clienteDados, $dados, $nome_servico, $taskId);
        Ws::dispatch($clienteDados, $dados, $vm_aplicacao, $nome_vm_aplicacao, $nome_servico, $taskId);

        //Swarm::dispatch($clienteDados['apelido'], $dados, $vm_aplicacao['porta'], $nome_vm_aplicacao, $taskId);
        return redirect('/deploy');
    }
        
}
