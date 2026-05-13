<?php

namespace App\Services\EnvioEmail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnvioEmailService
{
    public function enviar($emailDestino, $assunto, $mensagem, $anexo = null, $config = null)
    {
        try {

            // 🔥 Se tiver config do usuário, sobrescreve SMTP
            if ($config) {
                config([
                    'mail.mailers.smtp.host' => $config->host,
                    'mail.mailers.smtp.port' => $config->port,
                    'mail.mailers.smtp.username' => $config->username,
                    'mail.mailers.smtp.password' => $config->password ? decrypt($config->password) : null,
                    'mail.mailers.smtp.encryption' => $config->criptografia,

                    'mail.from.address' => $config->from_address,
                    'mail.from.name' => $config->from_name,
                ]);
            }

            Mail::raw($mensagem, function ($message) use ($emailDestino, $assunto, $anexo, $config) {

                $message->to($emailDestino)
                        ->subject($assunto);

                // 🔥 garante o from correto
                if ($config && $config->from_address) {
                    $message->from($config->from_address, $config->from_name);
                }

                if ($anexo && file_exists($anexo)) {
                    $message->attach($anexo);
                }
            });

            Log::info("Email enviado com sucesso para {$emailDestino}");

        } catch (\Throwable $e) {

            Log::error("Erro ao enviar email para {$emailDestino}: " . $e->getMessage());

            // importante pra queue retry
            throw $e;
        }
    }
}