<?php

namespace App\Repositories\UrlLauncher;

use Illuminate\Support\Facades\DB;
use App\Services\SqlMonitorService;
use Illuminate\Support\Facades\Cache;

class UrlLauncherRepository
{
    public function getUrlsLauncher(string $tipo): array
    {
        return DB::table('url_launcher')
            ->where('tipo', $tipo)
            ->pluck('url')
            ->toArray();
    }
}
