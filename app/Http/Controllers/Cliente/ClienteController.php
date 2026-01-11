<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use App\Repositories\Cliente\ClienteRepository;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClienteRepository $clienteRepository)
    {
        $clientes = $clienteRepository->listarCompleto();

        return view('cliente.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cliente.create');
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
        $apelido = $request->input('apelido');
        $porta = $request->input('porta');
        //$licenca = $request->input('licenca');
        $coletor = $request->input('coletor');
        $desktop = $request->input('desktop');
        $ativo = $request->input('ativo', 0);
        $licenca = $coletor + $desktop;
        $remoteapp = $apelido . '.rdp';

        $dados = [
            'nome' => $nome,
            'apelido' => $apelido,
            'porta_rdp' => $porta,
            'licenca' => $licenca,
            'coletor' => $coletor,
            'desktop' => $desktop,
            'ativo' => $ativo,
            'remoteapp' => $remoteapp,
        ];
        $id = DB::table('cliente_escala')->insertGetId($dados);

        return redirect('/cliente_escala');
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
        $dados = DB::table('cliente_escala')
        ->where('id_cliente_escala', $id)
        ->first();

        return view('cliente.edit')->with('dados', $dados);
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

        $coletor = $request->input('coletor');
        $desktop = $request->input('desktop');
        $licenca = $coletor + $desktop;
        $apelido = $request->input('apelido');
        $remoteapp = $apelido . '.rdp';

        DB::table('cliente_escala')
        ->where('id_cliente_escala', $id)
        ->update([
            'nome' => $request->nome,
            'apelido' => $request->apelido,
            'porta_rdp' => $request->porta,
            'licenca' => $licenca,
            'coletor' => $request->coletor,
            'desktop' => $request->desktop,
            'ativo' => $request->input('ativo', 0),
            'remoteapp' => $remoteapp,
        ]);
        return redirect('/cliente_escala');
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
            ->where('nomeConfig', 'url_api_cliente')
            ->value('valorConfig');


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

    public function gerardm()
    {
        $clientes = DB::table('cliente_escala')
    ->where('ativo', 1)
    ->whereNotNull('porta_rdp')
    ->get();

    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><RDMExport></RDMExport>');
    $connections = $xml->addChild('Connections');

    // Adiciona o grupo "local"
    $grupo = $connections->addChild('Connection');
    $grupo->addChild('AppVersion', '2025.1');
    $grupo->addChild('ConnectionType', 'Group');
    $grupo->addChild('Group', 'local');
    $grupo->addChild('ID', Str::uuid());
    $grupo->addChild('Name', 'local');
    $grupo->addChild('TemplateSourceID', Str::uuid());

    foreach ($clientes as $cliente) {
        //$url = strtoupper($cliente->apelido) . '.cloud.escalasoft.com.br:' . $cliente->porta_rdp;

        $url = $cliente->apelido 
    ? "{$cliente->apelido}.cloud.escalasoft.com.br:{$cliente->porta_rdp}" 
    : "cloud.escalasoft.com.br:{$cliente->porta_rdp}";

        $connection = $connections->addChild('Connection');
        $connection->addChild('Url', $url);
        $connection->addChild('AppVersion', '2025.1');
        $connection->addChild('ConnectionType', 'RDPConfigured');
        $connection->addChild('Group', 'local');
        $connection->addChild('ID', Str::uuid());
        $connection->addChild('Name', $cliente->nome);
        $connection->addChild('OpenEmbedded', 'true');
    }

    $xml->addChild('DatabaseID', Str::uuid());
    $xml->addChild('Version', '2');

    // Formatando o XML com indentação
    $dom = dom_import_simplexml($xml)->ownerDocument;
    $dom->formatOutput = true;
    $rdmContent = $dom->saveXML();

    return Response::make($rdmContent, 200, [
        'Content-Type' => 'application/xml',
        'Content-Disposition' => 'attachment; filename="clientes.rdm"',
    ]);
    }

}
