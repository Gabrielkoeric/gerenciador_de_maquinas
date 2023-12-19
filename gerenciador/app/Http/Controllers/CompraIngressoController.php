<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompraIngressoController extends Controller
{

    public function index(Request $request)
    {
        $resultados = DB::table('lote')
            ->join('ingressos', 'lote.id_ingressos', '=', 'ingressos.id_ingressos')
            ->select('lote.id_lote', 'lote.numero_lote', 'lote.quantidade_lote_disponivel', 'lote.adicional_lote', 'ingressos.nome', 'ingressos.descricao', 'ingressos.valor')
            ->where('lote.ativo', 1)
            ->get();

        $mensagemSucesso = $request->session()->get('mensagem.sucesso');

        return view('comprasIngresso.index')->with('resultados', $resultados)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lote_id' => ['required'],
        ]);
        $usuario = Auth::user()->id;
        $quantidade = $request->input('quantidade');
        $id_lote = $request->input('lote_id');
        $valor = 0;
        $hash = Str::random(35);

        if (count($id_lote) == count($quantidade)) {
            for ($i = 0; $i < count($id_lote); $i++) {
                $valorTotalIngresso = DB::table('lote as l')
                    ->select(DB::raw('(SELECT valor FROM ingressos WHERE id_ingressos = l.id_ingressos) + l.adicional_lote as valor_total'))
                    ->where('l.id_lote', $id_lote)
                    ->value('valor_total');
                $valor = $valor + ($valorTotalIngresso * $quantidade[$i]);
            }
        }

        $dados = [
            'id' => $usuario,
            'valor' => $valor,
            'status' => 'aguardando pagamento',
            'hash' => $hash
        ];
        $idInserido = DB::table('compras')->insertGetId($dados);

        if (count($id_lote) == count($quantidade)) {
            for ($i = 0; $i < count($id_lote); $i++) {
                for ($x = 0; $x < $quantidade[$i]; $x++){
                    $dados2 = [
                        'id_compra' => $idInserido,
                        'id_lote' => $id_lote[$i],
                        'permitir_nomeacao' => 1,
                    ];
                if ($quantidade[$i] > 0) {
                    DB::table('compra_ingresso')->insert($dados2);
                }
                }
            }
        }

        return redirect('/payment')->cookie('id', $idInserido)->cookie('valor', $valor)->cookie('hash', $hash);

    }
}
