<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GeraController extends Controller
{
    public function index()
    {
        $usuario = Auth::user()->id;

        $compras = DB::table('compras as c')
            ->select(
                'c.id_compra',
                'c.valor',
                'c.status',
                'c.hash',
                'c.link_pagamento',
                'c.entregue',
                'c.created_at',
                'c.updated_at',
                'u.id AS usuario_id',
                'u.nome_completo AS usuario_nome',
                'u.email AS usuario_email'
            )
            ->join('usuarios as u', 'c.id', '=', 'u.id')
            ->where('c.entregue', 0)
            ->where('u.id', $usuario)
            ->get();


        return view('gera.index')->with('compras', $compras);
    }

    public function create()
    {
        return view('gera.create');
    }

    public function store(Request $request)
    {
        $valor = $request->input('valor');
        $usuario = Auth::user()->id;
        $hash = Str::random(35);
        $status = "Aguardando pagamento";

        $dados = [
            'id' => $usuario,
            'valor' => $valor,
            'status' => $status,
            'entregue' => 0,
            'hash' => $hash
        ];
        $idInserido = DB::table('compras')->insertGetId($dados);

        return redirect('/payment')->cookie('id', $idInserido)->cookie('valor', $valor)->cookie('hash', $hash);
    }

    public function update(Request $request, $id)
    {
        DB::table('compras')
            ->where('id_compra', $id)
            ->update(['entregue' => 1]);

        return redirect('/gera');
    }
}
