<?php

namespace App\Repositories\ConfigGeral;

use Illuminate\Support\Facades\DB;
use App\Services\SqlMonitorService;
use Illuminate\Support\Facades\Cache;

class ConfigGeralRepository
{
    public function getConfigGeral(string $nome)
    {/*
        return Cache::remember(
            "config_geral_{$nome}",
            now()->addMinutes(1),
            function () use ($nome) {
                return DB::table('config_geral')
                    ->where('nomeConfig', $nome)
                    ->value('valorConfig');
            }
        );
    */
        return DB::table('config_geral')
            ->where('nomeConfig', $nome)
            ->value('valorConfig');
    
    }
}
