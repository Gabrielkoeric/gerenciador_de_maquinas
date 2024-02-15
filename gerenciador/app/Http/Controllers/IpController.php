<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\DownloadBandeiraJob;
use App\Jobs\teste;

class IpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ip = $request->input('ip');
        $cidade = $request->input('cidade');
        $regiao = $request->input('regiao');
        $continente = $request->input('continente');
        $id_incidente = $request->input('incidente');


        $query = DB::table('ip')->orderBy('cidade');

        // Aplicar condições WHERE com AND
        if (!empty($ip)) {
            $query->where('ip', $ip);
        }

        if (!empty($cidade)) {
            $query->where('cidade', $cidade);
        }

        if (!empty($regiao)) {
            $query->where('regiao', $regiao);
        }

        if (!empty($continente)) {
            $query->where('continente', $continente);
        }

        if (!empty($id_incidente)) {
            $query->where('id_incidente', $id_incidente);
        }

        $dados = $query->get();

        $incidentes = DB::table('ip')
            ->join('incidente as i', 'ip.id_incidente', '=', 'i.id_incidente')
            ->groupBy('i.id_incidente', 'i.nome')
            ->select('i.id_incidente', 'i.nome as nome_incidente')
            ->get();

        $cidades = DB::table('ip')
            ->select('cidade')
            ->distinct()
            ->get();

        $continentes = DB::table('ip')
            ->select('continente')
            ->distinct()
            ->get();

        $regioes = DB::table('ip')
            ->select('regiao')
            ->distinct()
            ->get();

        return view('ip.index')
            ->with('dados', $dados)
            ->with('incidentes', $incidentes)
            ->with('cidades', $cidades)
            ->with('continentes', $continentes)
            ->with('regioes', $regioes);
        //return view('ip.index')->with('dados', $dados)->with('incidentes', $incidentes)->with('incidenteAtual', $incidenteAtual);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ip.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arquivoIpPath = $request->file('arquivoIp')->store('arquivoIp', 'public');
        $request->arquivoIp = $arquivoIpPath;
        $nome = $request->input('nome');

        $dados = [
            'nome' => $nome,
            'arquivoIp' => $arquivoIpPath
        ];

        $id_incidente = DB::table('incidente')->insertGetId($dados);

        $fullFilePath = storage_path('app/public/' . $arquivoIpPath);
        $handle = fopen($fullFilePath, "r");

        while (($line = fgets($handle)) !== false) {
            // Use uma expressão regular para encontrar endereços IP na linha
            preg_match_all('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $line, $matches);

            foreach ($matches[0] as $ip) {
                $ip = trim($ip);

                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    $existingIp = DB::table('ip')->where('ip', $ip)->first();

                    if (!$existingIp) {
                        $url = "https://ipinfo.io/$ip/json?token=5e2c5aa71f13aa";
                        $response = file_get_contents($url);
                        $jsonData = json_decode($response);

                        if (!empty($jsonData->city)) {
                            $newData = [
                                'ip' => $ip,
                                'cidade' => $jsonData->city ?? null,
                                'regiao' => $jsonData->region ?? null,
                                'continente' => $jsonData->country ?? null,
                                'localizacao' => $jsonData->loc ?? null,
                                'empresa' => $jsonData->org ?? null,
                                'postal' => $jsonData->postal ?? null,
                                'timezone' => $jsonData->timezone ?? null,
                                'id_incidente' => $id_incidente,
                            ];

                            DB::table('ip')->insert($newData);

                            //DownloadBandeiraJob::dispatch($jsonData->country)->onQueue('padrao');
                           /* for ($i = 0; $i < 10000; $i++) {
                                Log::info('jobdisparado');
                                teste::dispatch()->onQueue('padrao');
                            }*/

                            // Exibe o IP encontrado
                            //echo "IP: " . $ip . "<br>";
                        }
                    }
                }
            }
        }

        fclose($handle);
        Log::info("incidente no store: $id_incidente");
        // Retirei o comentário para redirecionar após o processamento
        return redirect('/ip')->with('id_incidente', $id_incidente);
    }


    /*
    public function store(Request $request)
    {
        //dd($request);
        $imagemProdutoPath = $request->file('imagemProduto')->store('imagemProduto', 'public');
        $request->imagemProduto = $imagemProdutoPath;
        $nome = $request->input('nome');
        $quantidadeInicial = $request->input('quantidadeInicial');
        $quantidadeAtual = $request->input('quantidadeAtual');
        $valorCusto = $request->input('valorCusto');
        $valorVenda = $request->input('valorVenda');

        $produto_novo = new Estoques();
        $produto_novo->nome = $nome;
        $produto_novo->quantidade_inicial = $quantidadeInicial;
        $produto_novo->quantidade_atual = $quantidadeAtual;
        $produto_novo->valor_custo = $valorCusto;
        $produto_novo->valor_venda = $valorVenda;
        $produto_novo->imagemProduto = $imagemProdutoPath;
        $produto_novo->save();

        return redirect('/estoque')->with('mensagem.sucesso', 'Produto inserido com sucesso!');
    }
     */

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
