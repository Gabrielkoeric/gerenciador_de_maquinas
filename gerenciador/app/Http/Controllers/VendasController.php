<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendasController extends Controller
{
    public function index(Request $request)
    {
       $compras = DB::table('compras as c')
            ->select('c.id_compra', 'e.nome AS nome_produto', 'c.valor', 'c.status')
            ->join('compras_estoque as ce', 'c.id_compra', '=', 'ce.id_compra')
            ->join('estoques as e', 'ce.id_produto_estoque', '=', 'e.id_produto_estoque')
            ->get();

        $mensagemSucesso = $request->session()->get('mensagem.sucesso');
        return view('vendas.index')->with('compras', $compras)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function relatorio(){
       /* $compras = DB::table('compras as c')
            ->select('c.id_compra', 'e.nome AS nome_produto', 'c.valor', 'c.status')
            ->join('compras_estoque as ce', 'c.id_compra', '=', 'ce.id_compra')
            ->join('estoques as e', 'ce.id_produto_estoque', '=', 'e.id_produto_estoque')
            ->cursor();*/
        $compras = DB::table('compras AS co')
            ->select('p.nome AS nome_do_produto', 'c.quantidade_compra AS quantidade_vendida', 'p.valor_venda AS valor_unitario')
            ->join('compras_estoque AS c', 'co.id_compra', '=', 'c.id_compra')
            ->join('estoques AS p', 'c.id_produto_estoque', '=', 'p.id_produto_estoque')
            ->where('co.status', '=', 'approved')
            ->get();
        //dd($compras);
        $pdf = PDF::loadView('vendas.relatorio', ['compras' => $compras]);
        return $pdf->stream('Relatório_de_Vendas.pdf');
    }
}
