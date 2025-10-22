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
        $agora = Carbon::now();

        $inicioNoite = Carbon::today()->setHour(18)->setMinute(0)->setSecond(0);
        $fimNoite = (clone $inicioNoite)->addDay()->setHour(7)->setMinute(0)->setSecond(0);

        if ($agora->hour < 7) {
            $inicioNoite->subDay();
            $fimNoite->subDay();
        }

        $vms = DB::table('vm')
            ->where('tipo', 'rdp')
            ->where(function ($query) use ($inicioNoite) {
                $query->whereNull('created_at') // nunca reiniciada
                      ->orWhere('created_at', '<', $inicioNoite); // última reinicialização foi antes dessa noite
            })
            ->pluck('id_vm');
            //->select('id_vm')
            //->get();

        VerificaUsuarioLogadoVm::dispatch($vms);
    }
}
