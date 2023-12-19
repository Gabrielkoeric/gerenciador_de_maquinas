<?php

namespace App\Http\Controllers;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user()->id;
        $sqls = DB::table('produtos_disponiveis as pd')
            ->select('pd.id_produtos_disponiveis', 'e.id_produto_estoque', 'e.nome as nome_produto', 'pd.quantidade', 'e.imagemProduto')
            ->join('estoques as e', 'pd.id_produto_estoque', '=', 'e.id_produto_estoque')
            ->join('usuarios as u', 'pd.id', '=', 'u.id')
            ->where('u.id', '=', $usuario)
            ->where('pd.quantidade', '>', 0) // Adiciona esta linha para filtrar a quantidade maior que zero
            ->get();

        return view('produtos.index')->with('sqls', $sqls);
    }
    public function processaRetirada($hash)
    {
        $retiradaInfo = DB::table('retiradas')
            ->where('hash', $hash)
            ->where('validacao', 1)
            ->first();

        // Verificar se a hash existe no banco de dados
        if ($retiradaInfo) {
            $dados = DB::table('estoques AS e')
                ->select('e.nome AS nome_do_estoque', 'pdr.quantidade AS quantidade_retirada')
                ->join('produtos_disponiveis AS pd', 'e.id_produto_estoque', '=', 'pd.id_produto_estoque')
                ->join('produtos_disponiveis_retiradas AS pdr', 'pd.id_produtos_disponiveis', '=', 'pdr.id_produtos_disponiveis')
                ->join('retiradas AS r', 'pdr.id_retirada', '=', 'r.id_retirada')
                ->where('r.hash', "$hash")
                ->get();

            return view('produtos.entrega')->with('dados', $dados)->with('hash', $hash);
        } else {
            abort(404);
        }
    }

    public function concluido(Request $request){
        $hash = $request->query('hash');
        DB::table('retiradas')
            ->where('hash', $hash)
            ->update(['validacao' => false]);
        return to_route('home.index');
    }

    public function store(Request $request)
    {
        $dadosRequisicao = $request;

        $hash = Str::random(35);
        $url = env('APP_URL');
        $uri = "$url/produto_entrega/$hash";
        $quantidades = $request->input('quantidade');
        $nomes = $request->input('nome');
        $id_produto_estoque = $request->input('id_produtos_disponiveis');
        //for ($i = 0; $i < 10000; $i++) {
        $dados = [
            'hash' => $hash
        ];
    //}

        $idRetirada = DB::table('retiradas')->insertGetId($dados);

        foreach ($quantidades as $id_produtos_disponiveis => $quantidade) {

            $dados2 = [
                'id_produtos_disponiveis' => $id_produtos_disponiveis,
                'id_retirada' => $idRetirada,
                'quantidade' => $quantidade,
            ];
            if ($quantidade > 0) {
                DB::table('produtos_disponiveis_retiradas')->insertGetId($dados2);
            }
        }
        //gerar o qrcode
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($uri)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();
        $qrCodeImage = $result->getString();
        $nomeArquivo = uniqid('qrcode_') . '.png';
        $caminhoArquivo = 'qrcode/' . $nomeArquivo; // Caminho do arquivo no disco 'public'
        Storage::disk('public')->put($caminhoArquivo, $qrCodeImage);
        return view('produtos.qrcode')->with('qrcode', $caminhoArquivo);
    }
}
