<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\Deploy\Server;
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
            //return view('deploy.swarm');
            dd("escala swarm");
        case 'EscalaWeb':
            //return view('deploy.web');
            dd("escala web");
        case 'EscalaWebService':
            //return view('deploy.webservice');
            dd("escala web service 2");
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
}
