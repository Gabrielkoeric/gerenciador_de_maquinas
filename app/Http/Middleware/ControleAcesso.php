<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ControleAcesso
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return to_route('login');
        }

        $usuario = Auth::user()->id;


        // Consulta para obter os perfis do usuário
        $perfisDoUsuario = DB::table('usuario_perfil')
            ->where('id', $usuario)
            ->pluck('id_perfil')
            ->all();

        $rotaAtual = $request->route()->getName();
        Log::info('rota acessada', ['data' => $rotaAtual]);
        $nomeDaTela = explode('.', $rotaAtual)[0];
        $temPermissao = false;


        // Percorre os perfis do usuário e verifica a permissão em cada um
        foreach ($perfisDoUsuario as $perfilId) {
            $temPermissao = DB::table('perfil_permissao')
                ->join('home', 'perfil_permissao.id_home', '=', 'home.id_home')
                ->where('perfil_permissao.id_perfil', $perfilId)
                ->where('home.nome_tela', $nomeDaTela)
                ->exists();

            if ($temPermissao) {
                break; // Sai do loop assim que a permissão for encontrada
            }
        }

        if ($temPermissao) {
            return $next($request);
        }
        return redirect('/forbidden');
    }
}
