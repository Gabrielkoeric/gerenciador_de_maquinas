<?php

namespace App\Http\Controllers;

use App\Models\Estoques;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EstoqueController extends Controller
{
    public function index(Request $request)
    {
        $estoques = Estoques::all();
        $mensagemSucesso = $request->session()->get('mensagem.sucesso');
        return view('estoques.index')->with('estoques', $estoques)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        return view('estoques.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|min:3',
            'quantidadeInicial' => 'required|numeric',
            'quantidadeAtual' => 'required|numeric',
            'valorCusto' => 'required|numeric',
            'valorVenda' => 'required|numeric',
            'imagemProduto' => 'required|image|mimes:png,jpeg,gif|max:1000',
        ]);

        $imagemProdutoPath = $request->file('imagemProduto')->store('imagemProduto', 'public');
        $request->imagemProduto = $imagemProdutoPath;
        $nome = $request->input('nome');
        $quantidadeInicial = $request->input('quantidadeInicial');
        $quantidadeAtual = $request->input('quantidadeAtual');
        $valorCusto = $request->input('valorCusto');
        $valorVenda = $request->input('valorVenda');

        $dados = [
            'nome' => $nome,
            'quantidade_inicial' => $quantidadeInicial,
            'quantidade_atual' => $quantidadeAtual,
            'valor_custo' => $valorCusto,
            'valor_venda' => $valorVenda,
            'imagemProduto' => $imagemProdutoPath,
        ];
        DB::table('estoques')->insertGetId($dados);

        return redirect('/estoque')->with('mensagem.sucesso', 'Produto inserido com sucesso!');
    }

    public function edit(Estoques $estoque)
    {
        return view('estoques.edit')->with('estoque', $estoque);
    }

    public function update($id_produto_estoque, Request $request)
    {
        $request->validate([
            'nome' => 'required|min:3',
            'quantidadeInicial' => 'required|numeric',
            'quantidadeAtual' => 'required|numeric',
            'valorCusto' => 'required|numeric',
            'valorVenda' => 'required|numeric',
            'imagemProduto' => 'required|image|mimes:png,jpeg,gif|max:1000',
        ]);

        $imagemProdutoPath = $request->file('imagemProduto')->store('imagemProduto', 'public');

        DB::table('estoques')
            ->where('id_produto_estoque', $id_produto_estoque)
            ->update([
                'nome' => $request->nome,
                'quantidade_inicial' => $request->quantidadeInicial,
                'quantidade_atual' => $request->quantidadeAtual,
                'valor_custo' => $request->valorCusto,
                'valor_venda' => $request->valorVenda,
                'imagemProduto' => $imagemProdutoPath
            ]);

        return redirect()->route('estoque.index')->with('mensagem.sucesso', 'Produto Alterado com Sucesso!');
    }

    public function destroy(Estoques $estoque)
    {
        Storage::delete($estoque->imagemProduto);
        $estoque->delete();

        return to_route('estoque.index')->with('mensagem.sucesso', 'Produto Removido com Sucesso do Estoque!');
    }

}
