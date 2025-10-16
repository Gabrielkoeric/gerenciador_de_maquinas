<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RcloneLogsExecucoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $logs = DB::table('rclone_execucoes')
    ->join('repositorios', 'rclone_execucoes.id_repositorio', '=', 'repositorios.id_repositorios')
    ->join('cliente_escala', 'repositorios.id_cliente_escala', '=', 'cliente_escala.id_cliente_escala')
    ->select(
        'rclone_execucoes.*',
        'repositorios.nome as repositorioNome',
        'cliente_escala.nome as clienteNome'
    )
    ->orderByRaw('inicio IS NULL DESC') // NULLs primeiro
    ->orderBy('inicio', 'desc')        // depois ordem decrescente
    //->where('cliente_escala.nome', '=', '')
    //->where('rclone_execucoes.tipo', '=', 'manual')
    //->get();
    ->paginate(200);

return view('rclone_execucoes.index')->with('logs', $logs);

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
