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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NomeacaoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user()->id;
        $sqls = DB::table('controle_ingressos AS ci')
            ->join('compra_ingresso AS ci2', 'ci.id_compra_ingresso', '=', 'ci2.id_compra_ingresso')
            ->join('compras AS c', 'ci2.id_compra', '=', 'c.id_compra')
            ->join('usuarios AS u', 'ci.id', '=', 'u.id')
            ->join('lote AS l', 'ci2.id_lote', '=', 'l.id_lote')
            ->join('ingressos AS i', 'l.id_ingressos', '=', 'i.id_ingressos')
            ->where('c.id', '=', $usuario) // Substitua 1 pelo ID do usuário logado
            ->select('ci.id_controle_ingresso', 'i.nome AS nome_ingresso', 'u.email AS email_usuario')
            ->get();

        $mensagemSucesso = $request->session()->get('mensagem.sucesso');
        return view('nomeacao.index')->with('sqls', $sqls)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        $usuario = Auth::user()->id;

        $sqls = DB::table('compra_ingresso AS CI')
            ->select('CI.id_compra_ingresso AS id_compra_ingresso', 'I.nome AS nome_ingresso')
            ->join('lote AS L', 'CI.id_lote', '=', 'L.id_lote')
            ->join('ingressos AS I', 'L.id_ingressos', '=', 'I.id_ingressos')
            ->join('compras AS C', 'CI.id_compra', '=', 'C.id_compra')
            ->where('CI.permitir_nomeacao', 1)
            ->where('C.status', 'approved')
            ->where('C.id', $usuario)
            ->get();

        return view('nomeacao.create')->with('sqls', $sqls);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ingresso' => ['required'],
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');
        $id = $request->input('ingresso');

        $usuario = DB::table('usuarios')
            ->select('id')
            ->where('email', $email)
            ->first();

        if (!$usuario) {
            $idUsuario = DB::table('usuarios')->insertGetId(['email' => $email]);
            DB::table('usuario_perfil')->insert([
                [
                    'id' => $idUsuario,
                    'id_perfil' => 2,
                ]
            ]);
        } else {
            $idUsuario = $usuario->id;
        }

        $hash = Str::random(35);

        //gerar qrcode
        $url = env('APP_URL');
        $uri = "$url/check_liberacao/$hash";
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
        $nomeArquivo = uniqid('qrcode_ingresso_') . '.png';
        $caminhoArquivo = 'ingresso/' . $nomeArquivo; // Caminho do arquivo no disco 'public'
        Storage::disk('public')->put($caminhoArquivo, $qrCodeImage);

        $dados = [
            'hash' => $hash,
            'check-in' => 1,
            'check-out' => 0,
            'qrcode' => $caminhoArquivo,
            'id_compra_ingresso' => $id,
            'id' => $idUsuario
        ];
        DB::table('controle_ingressos')->insert($dados);
        DB::table('compra_ingresso')->where(['id_compra_ingresso' => $id])->update(['permitir_nomeacao' => 0]);
        return to_route('nomeacao.index');
    }
}
