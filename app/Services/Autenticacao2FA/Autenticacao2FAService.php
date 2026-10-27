<?php

namespace App\Services\Autenticacao2FA;

use App\Jobs\EnviarEmailJob;
use App\Repositories\Autenticacao2FA\Autenticacao2FARepository;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class Autenticacao2FAService
{
    protected $repository;

    public function __construct(
        Autenticacao2FARepository $repository
    ) {
        $this->repository = $repository;
    }

    public function iniciar($usuario)
    {

        $codigo = str_pad(
            (string) random_int(0, 99999999),
            8,
            '0',
            STR_PAD_LEFT
        );

        // Cria o desafio e armazena somente o hash do código.
        $desafio = $this->repository->criar([
            'id_usuario' => $usuario->id,
            'codigo_hash' => Hash::make($codigo),
            'expira_em' => now()->addMinutes(5),
            'tentativas' => 0,
            'validado_em' => null,
        ]);

        try {
            // Reutiliza o Job de envio de e-mail já existente.
            EnviarEmailJob::dispatch(
                $usuario->email,
                'Código de autenticação - Escalasoft',
                "Seu código de autenticação é: {$codigo}\n\n"
                    . "Este código expira em 5 minutos.\n"
                    . "Se você não tentou acessar o sistema, "
                    . "ignore este e-mail.",
                null,
                null,
                null
            );
        } catch (\Throwable $e) {
            $this->repository->invalidar(
                $desafio->id_autenticacao_2fa
            );

            throw $e;
        }

        session([
            '2fa_desafio_id' => $desafio->id_autenticacao_2fa,
            '2fa_validado' => false,
        ]);

        return true;
    }
}
