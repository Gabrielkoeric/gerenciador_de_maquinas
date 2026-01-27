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

    /**
     * Se não passar config, assume alerta_geral
     */
    public function __construct(string $mensagem, string $configNome = 'alerta_geral')
    {
        $this->mensagem   = $mensagem;
        $this->configNome = $configNome;
    }

    public function handle(ConfigGeralRepository $configRepo)
    {
        // busca config solicitada
        $chatId = $configRepo->getConfigGeral($this->configNome);

/*
        logger()->info('Telegram debug', [
    'config' => $this->configNome,
    'chatId' => $chatId
]);*/

        // fallback para alerta_geral
        if (!$chatId && $this->configNome !== 'alerta_geral') {
            $chatId = $configRepo->getConfigGeral('alerta_geral');
        }

        // se nem o padrão existir, não explode o job
        if (!$chatId) {
            logger()->warning('Chat Telegram não configurado', [
                'config' => $this->configNome,
                'mensagem' => $this->mensagem
            ]);
            return;
        }

        Notification::route('telegram', $chatId)->notify(new AlertaTelegram($this->mensagem));
    }
}
