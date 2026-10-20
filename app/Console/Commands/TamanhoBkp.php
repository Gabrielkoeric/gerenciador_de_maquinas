<?php

namespace App\Console\Commands;

use App\Services\TamanhoBkp\TamanhoBkpService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class TamanhoBkp extends Command
{
    protected $signature = 'tamanho:bkp';

    protected $description = 'Ajusta o tamanho dos bkps';

    public function handle(TamanhoBkpService $service)
    {
        Log::info('Iniciando coleta dos tamanhos dos backups.');

        try {
            $resultado = $service->coletar();

            Log::info('Coleta dos tamanhos dos backups finalizada.', $resultado);

            return Command::SUCCESS;
        } catch (Throwable $e) {
            Log::error('Erro ao coletar tamanhos dos backups.', [
                'erro' => $e->getMessage(),
            ]);

            return Command::FAILURE;
        }
    }
}