<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Repositories\LogSql\LogSqlRepository;
use App\Repositories\Rotina\RotinaRepository;

class SqlMonitorService
{

    private static bool $registrado = false;

    public static function start(int $rotina, int $acao)
    {
        if (self::$registrado) {
            return;
        }

        self::$registrado = true;

        $rotinaRepo = app(RotinaRepository::class);

        if (!$rotinaRepo->isDebugAtivo($rotina)) {
            return;
        }

        DB::listen(function ($query) use ($rotina, $acao) {

        if (
            str_contains($query->sql, 'logs_sql') ||
            str_contains($query->sql, 'sessions')
        ) {
            return;
        }

            // SQL com bindings substituídos
            $sqlInterpolado = self::interpolateQuery($query->sql, $query->bindings);

            /** @var LogSqlRepository $logRepo */
            $logRepo = app(LogSqlRepository::class);

            $logRepo->inserir([
                'id_rotina' => $rotina,
                'id_acao'   => $acao,
                'id'      => auth()->id(),
                'sql'          => $query->sql,
                'bindings'     => json_encode($query->bindings),
                'sql_full'     => $sqlInterpolado,
                'tempo_ms'     => $query->time,
                'connection'   => $query->connectionName,
                'database'     => DB::connection()->getDatabaseName(),
                'url'          => request()->fullUrl(),
                'rota'         => optional(request()->route())->getName(),
                'metodo_http'  => request()->method(),
                'ip'           => request()->ip(),
                'controller'   => optional(request()->route())->getActionName(),
                'executado_em' => now(),
            ]);

        });
    }

    private static function interpolateQuery(string $query, array $bindings): string
    {
        foreach ($bindings as $binding) {
            $binding = is_numeric($binding)
                ? $binding
                : "'" . addslashes($binding) . "'";

            $query = preg_replace('/\?/', $binding, $query, 1);
        }

        return $query;
    }
}
