<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*$servers = DB::table('servidor_fisico')->get();

        return view('servers.index')->with('servers', $servers);*/

        $servers = DB::table('servidor_fisico')
        ->leftJoin('usuario_servidor_fisico', 'servidor_fisico.id_servidor_fisico', '=', 'usuario_servidor_fisico.id_servidor_fisico')
        ->select('servidor_fisico.*', 'usuario_servidor_fisico.usuario', 'usuario_servidor_fisico.senha')
        ->get();

        return view('servers.index')->with('servers', $servers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('servers.create');
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
        $dns = $request->input('dns');
        $ip_wan = $request->input('ip_wan');
        $ip_lan = $request->input('ip_lan');
        $porta = $request->input('porta');
        $dominio = $request->input('dominio');
        $tipo = $request->input('tipo');
        $usuario = $request->input('usuario');
        $senha = $request->input('senha');

        $dados = [
            'nome' => $nome,
            'dns' => $dns,
            'ip_wan' => $ip_wan,
            'ip_lan' => $ip_lan,
            'porta' => $porta,
            'dominio' => $dominio,
            'tipo' => $tipo,
        ];
        $id = DB::table('servidor_fisico')->insertGetId($dados);

        $dados2= [
            
            'id_servidor_fisico' => $id,
            'usuario' => $usuario,
            'senha' => $senha,
        ];
        DB::table('usuario_servidor_fisico')->insertGetId($dados2);

        return redirect('/server')->with('mensagem.sucesso', 'Usuario inserido com sucesso!');
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
