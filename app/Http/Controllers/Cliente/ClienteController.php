<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use App\Repositories\Cliente\ClienteRepository;

class ClienteController extends Controller
{
    protected ClienteRepository $clienteRepo;

    public function __construct(ClienteRepository $clienteRepo)
    {
        $this->clienteRepo = $clienteRepo;
    }

    public function index(ClienteRepository $clienteRepository)
    {
        $clientes = $clienteRepository->listarCompleto();

        return view('cliente.index', compact('clientes'));
    }

    public function create()
    {
        return view('cliente.create');
    }

public function store(Request $request)
{
    $coletor = (int) $request->input('coletor', 0);
    $desktop = (int) $request->input('desktop', 0);

    $dados = [
        'nome'       => $request->input('nome'),
        'apelido'    => $request->input('apelido'),
        'porta_rdp'  => $request->input('porta'),
        'coletor'    => $coletor,
        'desktop'    => $desktop,
        'licenca'    => $coletor + $desktop,
        'ativo'      => $request->input('ativo', 0),
        'remoteapp'  => $request->input('apelido') . '.rdp',
    ];

    $this->clienteRepo->create($dados);

    return redirect('/cliente')->with('success', 'Cliente criado com sucesso!');
}

    public function show($id)
    {
        //
    }

    public function edit(int $id)
    {
        $dados = $this->clienteRepo->findById($id);

        return view('cliente.edit', compact('dados'));
    }

    public function update(Request $request, int $id)
    {
        $coletor = (int) $request->input('coletor', 0);
        $desktop = (int) $request->input('desktop', 0);

        $dados = [
            'nome'       => $request->input('nome'),
            'apelido'    => $request->input('apelido'),
            'porta_rdp'  => $request->input('porta'),
            'coletor'    => $coletor,
            'desktop'    => $desktop,
            'licenca'    => $coletor + $desktop,
            'ativo'      => $request->input('ativo', 0),
            'remoteapp'  => $request->input('apelido') . '.rdp',
        ];

        $this->clienteRepo->update($id, $dados);

        return redirect('/cliente');
    }

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
