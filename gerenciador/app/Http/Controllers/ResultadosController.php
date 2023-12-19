<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comprasEstoque = DB::table('compras as c')
            ->select('c.id_compra', 'e.nome AS nome_produto', 'c.valor', 'c.status')
            ->join('compras_estoque as ce', 'c.id_compra', '=', 'ce.id_compra')
            ->join('estoques as e', 'ce.id_produto_estoque', '=', 'e.id_produto_estoque')
            ->paginate(30); // 10 é o número de resultados por página

        $comprasIngressos = DB::table('compras as c')
            ->select('c.id_compra', 'i.nome AS nome_ingresso', 'c.valor', 'c.status')
            ->join('compra_ingresso as ci', 'c.id_compra', '=', 'ci.id_compra')
            ->join('ingressos as i', 'ci.id_lote', '=', 'i.id_ingressos')
            ->paginate(30);


        $totalCompraIngresso = DB::table('compras')
            ->join('compra_ingresso', 'compras.id_compra', '=', 'compra_ingresso.id_compra')
            ->where('compras.status', '=', 'approved')
            ->sum('compras.valor');

        $totalCompraEstoque = DB::table('compras')
            ->join('compras_estoque', 'compras.id_compra', '=', 'compras_estoque.id_compra')
            ->where('compras.status', '=', 'approved')
            ->sum('compras.valor');

        $totalValorCusto = DB::table('estoques')
            ->select(DB::raw('SUM(quantidade_inicial * valor_custo) as total_valor_custo'))
            ->first();
        $totalValorCusto = $totalValorCusto->total_valor_custo;

        $custos = DB::table('custos')->get();


        $mensagemSucesso = $request->session()->get('mensagem.sucesso');

        return view('resultados.index')->with('mensagemSucesso', $mensagemSucesso)
            ->with('comprasEstoque', $comprasEstoque)
            ->with('comprasIngressos', $comprasIngressos)
            ->with('totalCompraIngresso', $totalCompraIngresso)
            ->with('totalCompraEstoque', $totalCompraEstoque)
            ->with('totalValorCusto', $totalValorCusto)
            ->with('custos', $custos);
    }

    public function relatorioproduto(){
        $compras = DB::table('compras AS co')
            ->select('p.nome AS nome_do_produto', 'c.quantidade_compra AS quantidade_vendida', 'p.valor_venda AS valor_unitario')
            ->join('compras_estoque AS c', 'co.id_compra', '=', 'c.id_compra')
            ->join('estoques AS p', 'c.id_produto_estoque', '=', 'p.id_produto_estoque')
            ->where('co.status', '=', 'approved')
            ->get();
        //dd($compras);
        $pdf = PDF::loadView('resultados.relatorioproduto', ['compras' => $compras]);
        return $pdf->stream('Relatório_de_Vendas.pdf');
    }

    public function relatorioingresso(){
        $compras = DB::table('compras AS c')
            ->join('compra_ingresso AS ci', 'c.id_compra', '=', 'ci.id_compra')
            ->join('lote AS l', 'ci.id_lote', '=', 'l.id_lote')
            ->join('ingressos AS i', 'l.id_ingressos', '=', 'i.id_ingressos')
            ->select([
                'l.numero_lote AS numero_do_lote',
                'i.nome AS nome_do_ingresso',
                'i.valor AS valor_do_ingresso',
                'l.adicional_lote AS adicionalLote',
                DB::raw('COUNT(*) AS quantidade_vendida'),
            ])
            ->where('c.status', 'approved')
            ->groupBy('l.numero_lote', 'i.nome', 'i.valor', 'l.adicional_lote', 'c.valor')
            ->get();
        //dd($compras);
        $pdf = PDF::loadView('resultados.relatorioingresso', ['compras' => $compras]);
        return $pdf->stream('Relatório_de_Vendas.pdf');
    }

    public function relatorioresultados(){
        $totalCompraIngresso = DB::table('compras')
            ->join('compra_ingresso', 'compras.id_compra', '=', 'compra_ingresso.id_compra')
            ->where('compras.status', '=', 'approved')
            ->sum('compras.valor');

        $totalCompraEstoque = DB::table('compras')
            ->join('compras_estoque', 'compras.id_compra', '=', 'compras_estoque.id_compra')
            ->where('compras.status', '=', 'approved')
            ->sum('compras.valor');

        $totalValorCusto = DB::table('estoques')
            ->select(DB::raw('SUM(quantidade_inicial * valor_custo) as total_valor_custo'))
            ->first();
        $totalValorCusto = $totalValorCusto->total_valor_custo;

        $pdf = PDF::loadView('resultados.relatorioresultados', ['totalCompraIngresso' => $totalCompraIngresso, 'totalCompraEstoque'=>$totalCompraEstoque, 'totalValorCusto'=> $totalValorCusto ]);
        return $pdf->stream('Relatório_de_Resultados.pdf');
    }
}
