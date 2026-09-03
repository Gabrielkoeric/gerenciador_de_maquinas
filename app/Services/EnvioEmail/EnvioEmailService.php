<?php

namespace App\Services\EnvioEmail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnvioEmailService
{
    public function enviar($emailDestino, $assunto, $mensagem, $anexo = null, $config = null)
    {
        try {

            if ($config) {

                $mailerName = 'smtp_usuario_' .
                    $config->id_usuario_email_config . '_' .
                    uniqid();

                config([
                    "mail.mailers.{$mailerName}" => [
                        'transport' => 'smtp',
                        'host' => $config->host,
                        'port' => $config->port,
                        'username' => $config->username,
                        'password' => $config->password
                            ? decrypt($config->password)
                            : null,
                        'encryption' => $config->criptografia,
                    ],
                ]);

                Mail::mailer($mailerName)->raw(
                    $mensagem,
                    function ($message) use (
                        $emailDestino,
                        $assunto,
                        $anexo,
                        $config
                    ) {

                        $message->to($emailDestino)
                            ->subject($assunto);

                        if ($config->from_address) {
                            $message->from(
                                $config->from_address,
                                $config->from_name
                            );
                        }

                        if ($anexo && file_exists($anexo)) {
                            $message->attach($anexo);
                        }
                    }
                );

            } else {

                Mail::raw(
                    $mensagem,
                    function ($message) use (
                        $emailDestino,
                        $assunto,
                        $anexo
                    ) {

                        $message->to($emailDestino)
                            ->subject($assunto);

                        if ($anexo && file_exists($anexo)) {
                            $message->attach($anexo);
                        }
                    }
                );
            }

            Log::info("Email enviado com sucesso para {$emailDestino}");

        } catch (\Throwable $e) {

            Log::error(
                "Erro ao enviar email para {$emailDestino}: " .
                $e->getMessage()
            );

            throw $e;
        }
    }
}