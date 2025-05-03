<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DominioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dominios = DB::table('dominio')->get();

        return view('dominio.index')->with('dominios', $dominios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dominio.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nome = $request->input('nome');
        $usuario = $request->input('usuario');
        $senha = $request->input('senha');


        $dados = [
            'nome' => $nome,
            'usuario' => $usuario,
            'senha' => $senha,
        ];

        DB::table('dominio')->insertGetId($dados);

        return redirect('/dominios');
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
        $dominio = DB::table('dominio')->where('id_dominio', $id)->first();

        //dd($comando);
        return view('dominio.edit')->with('dominio', $dominio);
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
        DB::table('dominio')
            ->where('id_dominio', $id)
            ->update([
                'nome' => $request->nome,
                'usuario' => $request->usuario,
                'senha' => $request->senha,
            ]);
        return redirect('/dominios');
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
