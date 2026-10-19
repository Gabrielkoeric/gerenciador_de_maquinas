<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Bkp\Log;

class BkpLog extends Command
{
    protected $signature = 'bkp:log';

    protected $description = 'Envio de bkp de log para o server remoto';

    public function handle()
    {
        Log::dispatch()->onQueue('log');

        $this->info('Job de envio de backup LOG enviado para a fila log.');

        return Command::SUCCESS;
    }
}