<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Bkp\Full;

class BkpFull extends Command
{
    protected $signature = 'bkp:full';

    protected $description = 'Envio de bkp full para o server remoto';

    public function handle()
    {
        Full::dispatch()->onQueue('full');

        $this->info('Job de envio de backup FULL enviado para a fila log.');

        return Command::SUCCESS;
    }
}