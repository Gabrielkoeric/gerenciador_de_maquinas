<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecaoCloudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dados = DB::table('secao_cloud')
        ->join('cliente_escala', 'secao_cloud.id_cliente_escala', '=', 'cliente_escala.id_cliente_escala')
        ->select(
            'secao_cloud.id_secao_cloud',
            'secao_cloud.usuario',
            'secao_cloud.senha',
            'cliente_escala.nome as nome_cliente'
        )
        ->orderBy('cliente_escala.nome', 'asc')
        ->orderBy('secao_cloud.usuario', 'asc')
        ->get();
    
        return view('secao_cloud.index')->with('dados', $dados);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = DB::table('cliente_escala')
            ->select('id_cliente_escala', 'nome')
            ->get();
        return view('secao_cloud.create')->with('clientes', $clientes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuario = $request->input('usuario');
        $senha = $request->input('senha');
        $cliente = $request->input('cliente');

        $dados = [
            'usuario' => $usuario,
            'senha' => $senha,
            'id_cliente_escala' => $cliente,
        ];

        DB::table('secao_cloud')->insertGetId($dados);

        return redirect('/secao_cloud');
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
        
        $clientes = DB::table('cliente_escala')
            ->select('id_cliente_escala', 'nome')
            ->get();

            $clienteAtual = DB::table('secao_cloud')
            ->join('cliente_escala', 'secao_cloud.id_cliente_escala', '=', 'cliente_escala.id_cliente_escala')
            ->where('id_secao_cloud', $id)
            ->select('secao_cloud.*', 'cliente_escala.nome as nome_cliente')
            ->first();
        

        return view('secao_cloud.edit')->with('clientes', $clientes)->with('clienteAtual', $clienteAtual);
        
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
        DB::table('secao_cloud')
            ->where('id_secao_cloud', $id)
            ->update([
                'usuario' => $request->usuario,
                'senha' => $request->senha,
                'id_cliente_escala' => $request->cliente,
            ]);
        return redirect('/secao_cloud');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    DB::table('secao_cloud')->where('id_secao_cloud', $id)->delete();
    
    return redirect()->route('secao_cloud.index')
        ->with('mensagemSucesso', 'Registro excluído com sucesso!');
}
}
