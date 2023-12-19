<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user()->id;

        $compras = DB::table('compras as c')
            ->select('c.id_compra', 'e.nome AS nome_produto', 'c.valor', 'c.status', 'c.link_pagamento')
            ->join('compras_estoque as ce', 'c.id_compra', '=', 'ce.id_compra')
            ->join('estoques as e', 'ce.id_produto_estoque', '=', 'e.id_produto_estoque')
            ->where('id', $usuario) // Filtra as compras do usuário logado
            ->get();

        $mensagemSucesso = $request->session()->get('mensagem.sucesso');
        return view('pedidos.index')->with('compras', $compras)->with('mensagemSucesso', $mensagemSucesso);
    }

}
