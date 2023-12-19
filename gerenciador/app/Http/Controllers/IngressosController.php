<?php

namespace App\Http\Controllers;

use App\Models\Ingressos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class IngressosController extends Controller
{
    public function index(Request $request)
    {
        $ingressos = DB::table('ingressos')
            ->select('id_ingressos', 'nome', 'descricao', 'quantidade', 'quantidade_disponivel', 'valor')
            ->get();
        $mensagemSucesso = $request->session()->get('mensagem.sucesso');

        return view('ingressos.index')->with('ingressos', $ingressos)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        return view('ingressos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|min:3',
            'descricao' => 'required|min:8',
            'quantidade' => 'required|numeric',
            'quantidadeDisponivel' => 'required|numeric',
            'valor' => 'required|numeric',
        ]);

        $nome = $request->input('nome');
        $descricao = $request->input('descricao');
        $quantidade = $request->input('quantidade');
        $quantidadeDisponivel = $request->input('quantidadeDisponivel');
        $valor = $request->input('valor');

        $dados = [
            'nome' => $nome,
            'descricao' => $descricao,
            'quantidade' => $quantidade,
            'quantidade_disponivel' => $quantidadeDisponivel,
            'valor' => $valor
        ];
        DB::table('ingressos')->insertGetId($dados);
        return redirect('/ingressos')->with('mensagem.sucesso', 'Ingresso inserido com sucesso!');
    }

    public function edit(Ingressos $ingresso)
    {
     return view('ingressos.edit')->with('ingresso', $ingresso);
    }

    public function update(Request $request, $id_ingressos)
    {
        $request->validate([
            'nome' => 'required|min:3',
            'descricao' => 'required|min:8',
            'quantidade' => 'required|numeric',
            'quantidadeDisponivel' => 'required|numeric',
            'valor' => 'required|numeric',
        ]);

        DB::table('ingressos')
            ->where('id_ingressos', $id_ingressos)
            ->update([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'quantidade' => $request->quantidade,
                'quantidade_disponivel' => $request->quantidadeDisponivel,
                'valor' => $request->valor,
            ]);
        return redirect()->route('ingressos.index')->with('mensagem.sucesso', 'Ingresso Alterado com Sucesso!');
    }

    public function destroy(Ingressos $ingresso)
    {
        $ingresso->delete();
        return to_route('ingressos.index')->with('mensagem.sucesso', 'Ingresso Removido com Sucesso!');
    }
}
