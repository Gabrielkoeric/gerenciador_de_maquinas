<?php

namespace App\Jobs\Notificacao;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\AlertaTelegram;
use Illuminate\Support\Facades\Notification;

class Telegram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mensagem;
    //public $dado;

    

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mensagem/*, $dado*/)
    {
        $this->mensagem = $mensagem;
        //$this->dado = $dado;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Notification::route('telegram', 5779378630)->notify(new AlertaTelegram("{$this->mensagem}"));
        Notification::route('telegram', '-1002841506181')->notify(new AlertaTelegram("{$this->mensagem}"));

    }
}
