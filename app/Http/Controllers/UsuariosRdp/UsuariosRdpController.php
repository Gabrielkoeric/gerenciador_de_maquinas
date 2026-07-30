<?php

namespace App\Http\Controllers\UsuariosRdp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsuariosRdpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
    $rdps = \DB::table('vm')
        ->select(
            'vm.id_vm',
            'vm.nome'
        )
        ->where('vm.tipo', 'rdp')
        ->orderBy('vm.nome')
        ->get();

    foreach ($rdps as $rdp) {

        $clientes = \DB::table('servico_vm')
            ->join('cliente_escala', 'cliente_escala.id_cliente_escala', '=', 'servico_vm.id_cliente_escala')
            ->leftJoin('secao_cloud', 'secao_cloud.id_cliente_escala', '=', 'cliente_escala.id_cliente_escala')
            ->select(
                'cliente_escala.id_cliente_escala',
                'cliente_escala.nome',
                \DB::raw('COUNT(secao_cloud.id_secao_cloud) as total_secoes')
            )
            ->where('servico_vm.id_vm', $rdp->id_vm)
            ->groupBy(
                'cliente_escala.id_cliente_escala',
                'cliente_escala.nome'
            )
            ->orderBy('cliente_escala.nome')
            ->get();

        $rdp->clientes = $clientes;

        $rdp->total_clientes = $clientes->count();

        $rdp->total_secoes = $clientes->sum('total_secoes');
    }

    return view('usuariosrdp.index', compact('rdps'));
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
