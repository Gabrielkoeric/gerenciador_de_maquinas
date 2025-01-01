<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = DB::table('usuarios as u')
            ->select('u.id', 'u.nome_completo', 'u.email', 'u.imagem', 'u.celular', 'p.nome as nome_perfil')
            ->leftJoin('usuario_perfil as up', 'u.id', '=', 'up.id')
            ->leftJoin('perfil as p', 'up.id_perfil', '=', 'p.id_perfil')
            ->paginate(100);
        $mensagemSucesso = $request->session()->get('mensagem.sucesso');

        return view('usuarios.index')->with('usuarios', $usuarios)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        $perfis = DB::table('perfil')
            ->select('id_perfil', 'nome')
            ->get();
        return view('usuarios.create')->with('perfis', $perfis);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'min:3'],
            'email' => ['required', 'email'], // Add the 'email' rule for email validation
            'celular' => ['required', 'min:10', 'max:11'],
            'perfil' => ['required']
        ]);

        $usuario = $request->input('nome');
        $email = $request->input('email');
        $celular = $request->input('celular');
        $permissao = $request->input('perfil');

        $dados = [
            'email' => $email,
            'celular' => $celular,
            'nome_completo' => $usuario,
        ];
        $id = DB::table('usuarios')->insertGetId($dados);

        $dados2= [
            'id' => $id,
            'id_perfil' => $permissao,
        ];

        DB::table('usuario_perfil')->insertGetId($dados2);

        return redirect('/usuario')->with('mensagem.sucesso', 'Usuario inserido com sucesso!');
    }

    public function edit(Usuarios $usuario)
    {
        $perfis = DB::table('perfil')
            ->select('id_perfil', 'nome')
            ->get();

        $perfilAtual = DB::table('usuarios as u')
            ->select('p.nome as nome_perfil', 'p.id_perfil as id_perfil')
            ->leftJoin('usuario_perfil as up', 'u.id', '=', 'up.id')
            ->leftJoin('perfil as p', 'up.id_perfil', '=', 'p.id_perfil')
            ->where('u.id', '=', $usuario->id)
            ->first();
        return view('usuarios.edit')->with('usuario', $usuario)->with('perfis', $perfis)->with('perfilAtual', $perfilAtual);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'nome' => ['required', 'min:3'],
            'email' => ['required', 'email'], // Add the 'email' rule for email validation
            'celular' => ['required', 'min:10', 'max:11'],
            'perfil' => ['required']
        ]);

        DB::table('usuarios')
            ->where('id', $id)
            ->update([
                'email' => $request->email,
                'celular' => $request->celular,
                'nome_completo' => $request->nome,
            ]);
        DB::table('usuario_perfil')
            ->where('id', $id)
            ->update([
                'id_perfil' => $request->perfil,
            ]);

        return redirect()->route('usuario.index')->with('mensagem.sucesso', 'Usuário Alterado com Sucesso');
    }

    public function destroy(Usuarios $usuario)
    {
        $usuario->delete();
        return to_route('usuario.index')->with('mensagem.sucesso', 'Usuario Removido com Sucesso');
    }
}
