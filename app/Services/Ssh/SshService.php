<?php

namespace App\Services\Ssh;

use Symfony\Component\Process\Process;

class SshService
{
    public function executarComando(
        string $ip,
        string $usuario,
        string $senha,
        string $comando,
        ?int $timeout = null
    ): array {
            $sshCommand = sprintf(
                'sshpass -p %s ssh -o StrictHostKeyChecking=no %s %s',
                escapeshellarg($senha),
                escapeshellarg($usuario . '@' . $ip),
                escapeshellarg($comando)
            );

        $process = Process::fromShellCommandline($sshCommand);
        $process->setTimeout($timeout);
        $process->run();

        return [
            'sucesso' => $process->isSuccessful(),
            'codigo_saida' => $process->getExitCode(),
            'saida' => $process->getOutput(),
            'erro' => $process->getErrorOutput(),
        ];
    }

    public function lerArquivoLog(
        string $ip,
        string $usuario,
        string $senha,
        string $caminho
    ): array {
        return $this->executarComando(
            $ip,
            $usuario,
            $senha,
            'cat -- ' . escapeshellarg($caminho),
            null
        );
    }

    public function limpaPasta(
        string $ip,
        string $usuario,
        string $senha,
        string $caminho
    ): array {
        $caminho = rtrim($caminho, '/');
    
        $comando = 'find ' . escapeshellarg($caminho)
            . ' -mindepth 1 -maxdepth 1 -exec rm -rf -- {} +';
    
        return $this->executarComando(
            $ip,
            $usuario,
            $senha,
            $comando,
            null
        );
    }
}
