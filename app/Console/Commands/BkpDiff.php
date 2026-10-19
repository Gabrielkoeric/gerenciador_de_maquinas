<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Bkp\Log;

class BkpDiff extends Command
{
    protected $signature = 'bkp:diff';

    protected $description = 'Envio de bkp Diff para o server remoto';

    public function handle()
    {
        Log::dispatch()->onQueue('diff');

        $this->info('Job de envio de backup DIFF enviado para a fila log.');

        return Command::SUCCESS;
    }
}