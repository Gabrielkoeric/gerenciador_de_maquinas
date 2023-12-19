<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

class HomeController extends Controller
{
    public function index()
    {
        // Obtém o ID do usuário autenticado
        $usuario = Auth::user()->id;

        // Passo 1: Obter perfis do usuário
        $perfisDoUsuario = DB::table('usuario_perfil')
            ->where('id', $usuario)
            ->pluck('id_perfil')
            ->all();

        // Passo 2: Obter permissões relacionadas à tela atual para cada perfil
        $permissaoPorPerfil = [];
        foreach ($perfisDoUsuario as $perfilId) {
            $permissaoPorPerfil[$perfilId] = DB::table('perfil_permissao')
                ->where('id_perfil', $perfilId)
                ->pluck('id_home')
                ->all();
        }

        // Passo 3: Filtrar botões permitidos na tela atual
        $sqls = DB::table('home')
            ->whereIn('id_home', collect($permissaoPorPerfil)->flatten()->all())
            ->where('permite_home', 1)
            ->get();

        return view('home.index')->with('sqls', $sqls);
    }

}
