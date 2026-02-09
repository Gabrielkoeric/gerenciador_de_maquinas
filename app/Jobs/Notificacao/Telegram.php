<?php

namespace App\Jobs\Notificacao;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AlertaTelegram;
use App\Repositories\ConfigGeral\ConfigGeralRepository;

class Telegram implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $mensagem;
    public string $configNome;

    public function __construct(string $mensagem, string $configNome = 'alerta_geral')
    {
        $this->mensagem   = $mensagem;
        $this->configNome = $configNome;
    }

    public function handle(ConfigGeralRepository $configRepo)
    {
        $chatId = $configRepo->getConfigGeral($this->configNome);

        if (!$chatId && $this->configNome !== 'alerta_geral') {
            $chatId = $configRepo->getConfigGeral('alerta_geral');
        }

        if (!$chatId) {
            logger()->warning('Chat Telegram não configurado', [
                'config' => $this->configNome,
                'mensagem' => $this->mensagem
            ]);
            return;
        }
        sleep(2);
        Notification::route('telegram', $chatId)->notify(new AlertaTelegram($this->mensagem));
    }
}