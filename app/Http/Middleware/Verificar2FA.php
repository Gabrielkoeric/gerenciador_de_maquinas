<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Verificar2FA
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('2FA Middleware: iniciado', [
            'rota' => $request->route() ? $request->route()->getName() : null,
            'url' => $request->fullUrl(),
            'usuario_id' => auth()->id(),
            'autenticado' => auth()->check(),
            '2fa_validado' => session('2fa_validado'),
            '2fa_desafio_id' => session('2fa_desafio_id'),
        ]);
        // Sem usuário autenticado, deixa os middlewares de autenticação
        // existentes tratarem o acesso.
        if (!auth()->check()) {
            Log::info(
                '2FA Middleware: usuário não autenticado; seguindo para próximo middleware.'
            );
            return $next($request);
        }

        // Se o segundo fator já foi validado nesta sessão, permite o acesso.
        if (session('2fa_validado') === true) {

            Log::info(
                '2FA Middleware: segundo fator validado; liberando acesso.',
                ['usuario_id' => auth()->id()]
            );
            return $next($request);
        }

        // Evita redirecionar novamente caso já esteja iniciando o 2FA.
        if ($request->routeIs('2fa.*')) {

            Log::info(
                '2FA Middleware: rota do próprio 2FA; evitando redirecionamento circular.',
                ['rota' => $request->route()->getName()]
            );
            return $next($request);
        }

        Log::warning(
            '2FA Middleware: segundo fator pendente; redirecionando para iniciar.',
            [
                'usuario_id' => auth()->id(),
                'rota_atual' => $request->route()
                    ? $request->route()->getName()
                    : null,
                'destino' => '2fa.iniciar',
            ]
        );

        // Segundo fator pendente: direciona para iniciar o processo.
        return redirect()->route('2fa.iniciar');
    }
}
