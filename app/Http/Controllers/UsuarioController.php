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

        if ($request->filled('smtp_host') || $request->filled('smtp_user')) {
            DB::table('usuario_email_config')->insert([
                'id_usuario' => $id,
                'host' => $request->smtp_host,
                'port' => $request->smtp_port ?? 587,
                'username' => $request->smtp_user,
                'password' => $request->smtp_pass ? encrypt($request->smtp_pass) : null,
                'criptografia' => $request->smtp_encryption,
                'from_address' => $request->smtp_from,
                'from_name' => $request->smtp_from_name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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

        $emailConfig = DB::table('usuario_email_config')
            ->where('id', $usuario->id)
            ->first();

        return view('usuarios.edit')->with('usuario', $usuario)->with('perfis', $perfis)->with('perfilAtual', $perfilAtual)->with('emailConfig', $emailConfig);
    }

    public function update($id, Request $request)
    {
        //dd($request->all());
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

        DB::table('usuario_perfil')->updateOrInsert(
    ['id' => $id],
    ['id_perfil' => $request->perfil]
);

    if ($request->filled('smtp_host') || $request->filled('smtp_user')) {
        $configExiste = DB::table('usuario_email_config')
            ->where('id', $id)
            ->exists();

        $dadosEmail = [
            'host' => $request->smtp_host,
            'port' => $request->smtp_port ?? 587,
            'username' => $request->smtp_user,
            'criptografia' => $request->smtp_encryption,
            'from_address' => $request->smtp_from,
            'from_name' => $request->smtp_from_name,
            'updated_at' => now(),
        ];

        if (!empty($request->smtp_pass)) {
            $dadosEmail['password'] = encrypt($request->smtp_pass);
        }

        if ($configExiste) {
            DB::table('usuario_email_config')
                ->where('id', $id)
                ->update($dadosEmail);
        } else {
            $dadosEmail['id'] = $id;
            $dadosEmail['created_at'] = now();

            if (!isset($dadosEmail['password'])) {
                $dadosEmail['password'] = null;
            }

            DB::table('usuario_email_config')->insert($dadosEmail);
        }
    }
        return redirect()->route('usuario.index')->with('mensagem.sucesso', 'Usuário Alterado com Sucesso');
    }

    public function destroy(Usuarios $usuario)
    {
        $usuario->delete();
        return to_route('usuario.index')->with('mensagem.sucesso', 'Usuario Removido com Sucesso');
    }
}
