<?php

namespace App\Repositories\Rotina;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RotinaRepository
{
    public function isDebugAtivo(int $idRotina): bool
    {
        return Cache::remember(
            "rotina_debug_id_{$idRotina}",
            now()->addSeconds(60),
            function () use ($idRotina) {
                return DB::table('rotinas')
                    ->where('id_rotina', $idRotina)
                    ->where('debug', 1)
                    ->exists();
            }
        );
    }

    public function limparCache(int $idRotina): void
    {
        Cache::forget("rotina_debug_id_{$idRotina}");
    }
}
