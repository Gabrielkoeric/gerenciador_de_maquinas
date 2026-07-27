<?php

namespace App\Http\Controllers\VmUso;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VmUsoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
    $ultimaAuditoria = DB::table('horario_auditoria')
        ->max('id_horario_auditoria');

    $vms = DB::table('vm')
        ->leftJoin('servico_vm', 'servico_vm.id_vm', '=', 'vm.id_vm')
        ->leftJoin('auditoria_secao', function ($join) use ($ultimaAuditoria) {
            $join->on('auditoria_secao.id_cliente_escala', '=', 'servico_vm.id_cliente_escala')
                ->where('auditoria_secao.id_horario_auditoria', '=', $ultimaAuditoria);
        })
        ->select(
            'vm.id_vm',
            'vm.nome',
            DB::raw('SUM(IFNULL(auditoria_secao.quantidade,0)) as usuarios'),
            DB::raw('COUNT(DISTINCT CASE WHEN IFNULL(auditoria_secao.quantidade,0) > 0 THEN servico_vm.id_cliente_escala END) as clientes_online'),
            DB::raw('CASE
                        WHEN SUM(IFNULL(auditoria_secao.quantidade,0)) > 0
                        THEN 1
                        ELSE 0
                     END as online')
        )
        ->groupBy('vm.id_vm', 'vm.nome')
        ->orderBy('vm.nome')
        ->get();

    return view('vmuso.index', compact('vms'));
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
