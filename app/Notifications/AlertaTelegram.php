<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class AlertaTelegram extends Notification
{
    use Queueable;

    public function __construct(public string $mensagem) {}

    public function via($notifiable)
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->content($this->mensagem);
            ->options([
                'parse_mode' => null,
            ]);

    }

    public function toArray($notifiable)
    {
        return [
            'mensagem' => $this->mensagem,
        ];
    }
}
