<?php

namespace App\Http\Controllers;

use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class LoteController extends Controller
{
    public function index(Request $request)
    {
        $lotes = DB::table('lote')
            ->select('lote.id_lote', 'lote.numero_lote', 'lote.quantidade_lote', 'lote.quantidade_lote_disponivel', 'lote.ativo', 'lote.adicional_lote', 'ingressos.nome as nome_ingresso')
            ->join('ingressos', 'lote.id_ingressos', '=', 'ingressos.id_ingressos')
            ->get();
        $mensagemSucesso = $request->session()->get('mensagem.sucesso');

        return view('lote.index')->with('lotes', $lotes)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        $ingressos = DB::table('ingressos')
            ->select('id_ingressos', 'nome')
            ->get();

        return view('lote.create')->with('ingressos', $ingressos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'numeroLote' => 'required|numeric|min:1',
            'quantidadeLote' => 'required|numeric',
            'quantidadeLoteDisponivel' => 'required|numeric',
            'adicionalLote' => 'required|numeric',
            'ingresso' => 'required',
        ]);

        $numeroLote = $request->input('numeroLote');
        $quantidadeLote = $request->input('quantidadeLote');
        $quantidadeLoteDisponivel = $request->input('quantidadeLoteDisponivel');
        $ativo = $request->input('ativo');
        $adicionalLote = $request->input('adicionalLote');
        $ingresso = $request->input('ingresso');
        $ativo = ($ativo === 'on') ? 1 : 0;

        $dados = [
            'numero_lote' => $numeroLote,
            'quantidade_lote' => $quantidadeLote,
            'quantidade_lote_disponivel' => $quantidadeLoteDisponivel,
            'adicional_lote' => $adicionalLote,
            'ativo' => $ativo,
            'id_ingressos' => $ingresso
            ];
        DB::table('lote')->insertGetId($dados);

        return redirect('/lote')->with('mensagem.sucesso', 'Lote inserido com sucesso!');
    }

    public function edit(Lote $lote)
    {
        $ingressos = DB::table('ingressos')
            ->select('id_ingressos', 'nome')
            ->get();

        $ingressoAtual = DB::table('lote')
            ->select('lote.id_lote', 'ingressos.id_ingressos', 'ingressos.nome')
            ->join('ingressos', 'lote.id_ingressos', '=', 'ingressos.id_ingressos')
            ->where('lote.id_lote', $lote->id_lote)
            ->first();

        return view('lote.edit')->with('lote', $lote)->with('ingressos', $ingressos)->with('ingressoAtual', $ingressoAtual);
    }

    public function update(Request $request, $id_lote)
    {
        $request->validate([
            'numeroLote' => 'required|numeric|min:1',
            'quantidadeLote' => 'required|numeric',
            'quantidadeLoteDisponivel' => 'required|numeric',
            'adicionalLote' => 'required|numeric',
            'ingresso' => 'required',
        ]);

        $ativo = $request->ativo;
        $ativo = ($ativo === 'on') ? 1 : 0;
        DB::table('lote')
            ->where('id_lote', $id_lote)
            ->update([
                'numero_lote' => $request->numeroLote,
                'quantidade_lote' => $request->quantidadeLote,
                'quantidade_lote_disponivel' => $request->quantidadeLoteDisponivel,
                'ativo' => $ativo,
                'id_ingressos' => $request->ingresso,
            ]);
        return redirect()->route('lote.index')->with('mensagem.sucesso', 'Ingresso Alterado com Sucesso!');
    }

    public function destroy(Lote $lote)
    {
        $lote->delete();
        return to_route('lote.index')->with('mensagem.sucesso', 'Lote Removido com Sucesso!');
    }
}
