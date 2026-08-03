<?php

namespace App\Http\Middleware;

use App\Jobs\ApiLogJob;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $inicio = microtime(true);

        $response = $next($request);

        $tempo = round((microtime(true) - $inicio) * 1000);

        ApiLogJob::dispatch([
            'data_hora' => now(),
            'uuid' => $request->route('chave'),
            'ip' => $request->ip(),
            'metodo' => $request->method(),
            'rota' => $request->path(),
            'status' => $response->getStatusCode(),
            'tempo_ms' => $tempo,
            'tamanho_resposta' => strlen($response->getContent()),
            'user_agent' => $request->userAgent(),
        ]);

        return $response;
    }
}