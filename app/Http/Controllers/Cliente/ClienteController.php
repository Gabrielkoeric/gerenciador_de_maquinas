<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use App\Repositories\Cliente\ClienteRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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

    public function formGerarRdp()
    {
        return view('cliente.gerar_rdp');
    }

        public function gerarRdpPost(Request $request)
    {
        $request->validate([
            'dominio' => 'required|string'
        ]);

        $dominio = $request->dominio;

        $clientes = $this->clienteRepo->getClientesComRdp();

        if ($clientes->isEmpty()) {
            return back()->with('erro', 'Nenhum cliente encontrado.');
        }

        // Pasta com data e hora
        $timestamp = Carbon::now()->format('Ymd_His');
        $pasta = "remoteapp/{$timestamp}";

        Storage::disk('public')->makeDirectory($pasta);

        foreach ($clientes as $cliente) {

            $host = strtoupper($cliente->apelido) . $dominio;
            $porta = $cliente->porta_rdp;

            $conteudo = $this->templateRdp(
                $host,
                $porta,
                strtoupper($cliente->apelido)
            );

            $nomeArquivo = $cliente->apelido . '.rdp';

            Storage::disk('public')->put(
                "{$pasta}/{$nomeArquivo}",
                $conteudo
            );
        }

        return back()->with(
            'sucesso',
            "Arquivos RDP gerados em storage/app/public/{$pasta}"
        );
    }

        private function templateRdp(string $host, int $porta, string $apelido): string
    {
        return <<<RDP
redirectclipboard:i:1
redirectprinters:i:1
redirectcomports:i:1
redirectsmartcards:i:1
devicestoredirect:s:*
drivestoredirect:s:*
redirectdrives:i:1
session bpp:i:32
prompt for credentials on client:i:1
span monitors:i:1
use multimon:i:1
remoteapplicationmode:i:1
server port:i:{$porta}
allow font smoothing:i:1
promptcredentialonce:i:0
gatewayusagemethod:i:0
gatewayprofileusagemethod:i:1
gatewaycredentialssource:i:0
full address:s:{$host}:{$porta}
alternate shell:s:||{$apelido}_ESCALASOFT
remoteapplicationprogram:s:||{$apelido}_ESCALASOFT
remoteapplicationname:s:{$apelido}_ESCALASOFT
workspace id:s:{$host}
use redirection server name:i:1
loadbalanceinfo:s:tsv://MS Terminal Services Plugin.1.RDSessionCollect
screen mode id:i:2
compression:i:1
keyboardhook:i:2
audiocapturemode:i:0
videoplaybackmode:i:1
connection type:i:7
networkautodetect:i:1
bandwidthautodetect:i:1
displayconnectionbar:i:1
disable wallpaper:i:0
disable full window drag:i:1
bitmapcachepersistenable:i:1
authentication level:i:2
negotiate security layer:i:1
autoreconnection enabled:i:1
RDP;
    }

}
