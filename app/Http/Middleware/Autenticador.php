<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Autenticador
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
            $metodoAutenticacao = DB::table('config_geral')
                ->where('nome_config', 'metodo_autenticacao')
                ->value('valor_config');

            $rotaLogin = $metodoAutenticacao === 'google' ? 'login' : 'login_local';
            return to_route($rotaLogin);
        }
        
        return $next($request);
    }
}
