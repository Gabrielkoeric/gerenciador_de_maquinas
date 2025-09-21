<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResumoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $filtroServicos = $request->input('servicos', []); // ids dos serviços
    $filtroVms = $request->input('vms', []); // ids das VMs

    $query = DB::table('cliente_escala as c')
        ->leftJoin('servico_vm as sv', 'c.id_cliente_escala', '=', 'sv.id_cliente_escala')
        ->leftJoin('servico as s', 'sv.id_servico', '=', 's.id_servico')
        ->leftJoin('vm as v', 'sv.id_vm', '=', 'v.id_vm')
        ->leftJoin('ip_lan as ip', 'v.id_ip_lan', '=', 'ip.id_ip_lan')
        ->select(
            'c.id_cliente_escala',
            'c.apelido as cliente',
            's.id_servico',
            's.nome as servico',
            'sv.nome as nome_servico_vm',
            'sv.porta',
            'v.id_vm',
            'v.nome as vm_nome',
            'ip.ip as vm_ip'
        );

    if (!empty($filtroServicos)) {
        $query->whereIn('s.id_servico', $filtroServicos);
    }

    if (!empty($filtroVms)) {
        $query->whereIn('v.id_vm', $filtroVms);
    }

    $dados = $query->orderBy('c.nome')->orderBy('s.nome')->get();

    $todosServicos = DB::table('servico')->orderBy('nome')->get();
    $todasVms = DB::table('vm')->orderBy('nome')->get();

    return view('resumo.index', compact('dados', 'todosServicos', 'filtroServicos', 'todasVms', 'filtroVms'));
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
        //
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
}
