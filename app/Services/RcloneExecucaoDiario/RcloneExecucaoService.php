<?php

namespace App\Services\RcloneExecucaoDiario;

use Carbon\Carbon;
use Symfony\Component\Process\Process;
use App\Repositories\RcloneExecucao\RcloneExecucaoRepository;
use App\Repositories\Repositorio\RepositorioRepository;
use App\Repositories\UsuarioVm\UsuarioVmRepository;
use Illuminate\Support\Facades\Log;

class RcloneExecucaoService
{
    protected RcloneExecucaoRepository $rcloneExecucaoRepository;
    protected RepositorioRepository $repositorioRepository;
    protected UsuarioVmRepository $usuarioVmRepository;
    protected ZabbixSenderService $zabbix;

    public function __construct(
        RcloneExecucaoRepository $rcloneExecucaoRepository,
        RepositorioRepository $repositorioRepository,
        UsuarioVmRepository $usuarioVmRepository,
        ZabbixSenderService $zabbix
    ) {
        $this->rcloneExecucaoRepository   = $rcloneExecucaoRepository;
        $this->repositorioRepository = $repositorioRepository;
        $this->usuarioVmRepository  = $usuarioVmRepository;
        $this->zabbix                = $zabbix;
    }

    public function executar(int $idExecucao): void
    {
        $inicio = now();

        $rcloneExecucaoRepository = $this->rcloneExecucaoRepository->findById($idExecucao);

        $this->rcloneExecucaoRepository->marcarExecutando($idExecucao);

        $repo = $this->repositorioRepository->buscarCompletoPorId($rcloneExecucaoRepository->id_repositorio);

        $usuarioVmRepository = $this->usuarioVmRepository->buscarPorVm($repo->id_server_bkp);

        $data = Carbon::now()->subHour();
        $dataPath = $data->format('Y/m/d');

        $remotePath = "\"{$repo->rclone}{$repo->origem}{$dataPath}/\"";
        $destino    = "{$repo->destino}{$dataPath}/";
        $logFile    = $repo->log_dir;
        $tags       = $repo->tags ?? '';
        $tipoCopia  = $repo->tipo_copia;

        $cmd = "rclone {$tipoCopia} {$remotePath} {$destino} --log-file=\"{$logFile}\" {$tags}";

        Log::info("Comando Rclone montado para execução: {$cmd}");

        $senhaSegura = escapeshellarg($usuarioVmRepository->senha);
        $sshCommand  = "sshpass -p $senhaSegura ssh -o StrictHostKeyChecking=no {$usuarioVmRepository->usuario}@{$repo->ip} \"$cmd\"";

        $process = Process::fromShellCommandline($sshCommand);
        $process->setTimeout(null);
        $process->run();

        $fim = now();
        $duration = $inicio->diffInSeconds($fim);

        $hostZabbix = $repo->apelido;

        if ($process->isSuccessful()) {
            $sshLog = "sshpass -p $senhaSegura ssh -o StrictHostKeyChecking=no {$usuarioVmRepository->usuario}@{$repo->ip} 'tail -n 1 {$logFile}'";
            $processLog = Process::fromShellCommandline($sshLog);
            $processLog->setTimeout(30);
            $processLog->run();

            $ultimaLinha = trim($processLog->getOutput());

            $qtdTransferidos = 0;
            $bytesTransferidos = 0;
            $qtdCheck = 0;

            $json = json_decode($ultimaLinha, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                $stats = $json['stats'] ?? [];
                $qtdTransferidos   = $stats['transfers'] ?? 0;
                $qtdCheck          = $stats['checks'] ?? 0;
                $bytesTransferidos = $stats['bytes'] ?? 0;
            }

            $this->rcloneExecucaoRepository->finalizarSucesso(
                $idExecucao,
                $cmd,
                $fim,
                $logFile,
                $ultimaLinha,
                $qtdTransferidos,
                $qtdCheck,
                $bytesTransferidos
            );

            $this->zabbix->send($hostZabbix, 'backup.files', $qtdTransferidos);
            $this->zabbix->send($hostZabbix, 'backup.bytes', $bytesTransferidos);
            $this->zabbix->send($hostZabbix, 'backup.duration', $duration);
            $this->zabbix->send($hostZabbix, 'backup.status', 1);

        } else {

            $erro = $process->getErrorOutput();

            $this->rcloneExecucaoRepository->finalizarErro(
                $idExecucao,
                $cmd,
                $fim,
                $logFile,
                $erro
            );

            $this->zabbix->send($hostZabbix, 'backup.status', 0);
            $this->zabbix->send($hostZabbix, 'backup.duration', $duration);
        }
    }
}