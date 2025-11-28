<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Jobs\Vm\VerificaUsuarioLogadoVm;

class ReiniciaRdp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reinicia:rdp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
    
        $vms = DB::table('vm')
            ->where('tipo', 'rdp')
            ->orderBy('nome')
            ->pluck('id_vm');

        VerificaUsuarioLogadoVm::dispatch($vms);
    }
}
