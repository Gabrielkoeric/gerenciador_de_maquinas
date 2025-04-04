<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteEscalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = DB::table('cliente_escala')->get();

        return view('cliente.index')->with('clientes', $clientes);
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

    public function buscarClientes()
    {   
        $config = DB::table('config_geral')
            ->where('nome_config', 'url_api_cliente')
            ->value('valor_config');


        $url = "$config";

        // Fazendo a requisição GET na API
        $response = file_get_contents($url);
        $clientes = json_decode($response, true); // Decodifica a resposta JSON

        // Verifica se a resposta da API contém dados válidos
        if (!is_array($clientes) || empty($clientes)) {
            return redirect()->route('cliente_escala.index')->with('mensagemSucesso', 'Nenhum cliente encontrado.');
        }

        // Inserindo os dados na tabela cliente_escala
        foreach ($clientes as $cliente) {
            // Verifica se as chaves esperadas existem antes de inserir
            if (!isset($cliente['Nome']) || !isset($cliente['Licencas'])) {
                continue; // Pula esse item se os dados estiverem incompletos
            }

            DB::table('cliente_escala')->updateOrInsert(
                ['nome' => $cliente['Nome']], // Verifica se já existe
                [
                    'licenca' => $cliente['Licencas'],
                    'coletor' => $cliente['coletor'] ?? 0, // Se não existir, define como 0
                    'desktop' => $cliente['desktop'] ?? 0,
                    'ativo' => $cliente['ativo'] ?? 1, // Assume que ativo = 1 (verdadeiro) por padrão
                    'updated_at' => now(),
                ]
            );
        }
        return redirect()->route('cliente_escala.index')->with('mensagemSucesso', 'Clientes importados com sucesso!');
    }

}
