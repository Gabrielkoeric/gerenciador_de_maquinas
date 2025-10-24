<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Cron\CronExpression;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    /*
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('servicos:processar')->cron('37 23 * * *');
        $schedule->command('bkp:diario')->cron('1 * * * *');
        $schedule->command('reinicia:rdp')->everyThirtyMinutes()->between('18:00', '07:59'); 
    }
*/

    protected function schedule(Schedule $schedule)
    {
        // Busca todos os agendamentos ativos
        $agendamentos = DB::table('agendamentos')
            ->where('active', true)
            ->get();
    
        foreach ($agendamentos as $agendamento) {
            // Busca os horários ativos para cada agendamento
            $horarios = DB::table('horarios_agendamentos')
                ->where('id_agendamentos', $agendamento->id_agendamentos)
                ->where('active', true)
                ->get();
        
            foreach ($horarios as $horario) {
                $job = $schedule->command($agendamento->command);
            
                // Se tiver expressão cron, aplica
                if ($horario->expression) {
                    $job->cron($horario->expression);
                }
            
                // Se tiver meta com between_start e between_end
                if ($horario->meta) {
                    $meta = json_decode($horario->meta, true);
                    if (isset($meta['between_start']) && isset($meta['between_end'])) {
                        $job->between($meta['between_start'], $meta['between_end']);
                    }
                }

                // Após rodar o comando, atualiza as execuções
                $job->after(function () use ($agendamento, $horario) {
                    $cron = new CronExpression($horario->expression);

                    DB::table('agendamentos')
                        ->where('id_agendamentos', $agendamento->id_agendamentos)
                        ->update([
                            'last_run_at' => Carbon::now(),
                            'next_run_at' => $cron->getNextRunDate(Carbon::now())->format('Y-m-d H:i:s'),
                        ]);
                });
            }
        }
    }
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
