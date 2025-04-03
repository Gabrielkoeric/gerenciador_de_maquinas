<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComandoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comandos = DB::table('comando_execucao_remota')->get();

        return view('comando.index')->with('comandos', $comandos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('comando.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo = $request->input('tipo');
        $acao = $request->input('acao');
        $comando = $request->input('comando');

        $dados = [
            'tipo' => $tipo,
            'acao' => $acao,
            'comando' => $comando,
        ];

        DB::table('comando_execucao_remota')->insertGetId($dados);

        return redirect('/comando');
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
        $comando = DB::table('comando_execucao_remota')->where('id_comando_execucao_remota', $id)->first();

        //dd($comando);
        return view('comando.edit')->with('comando', $comando);
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
        DB::table('comando_execucao_remota')
            ->where('id_comando_execucao_remota', $id)
            ->update([
                'tipo' => $request->tipo,
                'acao' => $request->acao,
                'comando' => $request->comando,
            ]);
        return redirect('/comando');
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
