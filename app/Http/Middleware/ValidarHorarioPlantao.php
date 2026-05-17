<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidarHorarioPlantao
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $usuario = Auth::user();

        if (!$usuario->valida_horario) {
            return $next($request);
        }

        $possuiPlantao = DB::table('usuarios_plantoes')
            ->where('id', $usuario->id)
            ->where('inicio', '<=', now())
            ->where('fim', '>=', now())
            ->exists();

        if (!$possuiPlantao) {
            Auth::logout();
            return redirect('/fora-horario');
        }
        return $next($request);
    }
}