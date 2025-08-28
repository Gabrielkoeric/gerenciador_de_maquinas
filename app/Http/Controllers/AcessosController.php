<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcessosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $acessos = DB::table('acessos')->get();

        return view('acessos.index')->with('acessos', $acessos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('acessos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $link = $request->input('link');
        $usuario = $request->input('usuario');
        $senha = $request->input('senha');
        $descricao = $request->input('descricao');

        $dados = [
            'link' => $link,
            'usuario' => $usuario,
            'senha' => $senha,
            'descricao' => $descricao
        ];
        DB::table('acessos')->insertGetId($dados);

        return redirect('/acessos');
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
        $dados = DB::table('acessos')
        ->where('id_acesso', $id)
        ->first();

        return view('acessos.edit')->with('dados', $dados);
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
        DB::table('acessos')
        ->where('id_acesso', $id)
        ->update([
            'link' => $request->link,
            'usuario' => $request->usuario,
            'senha' => $request->senha,
            'descricao' => $request->descricao
        ]);
        return redirect('/acessos');
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
