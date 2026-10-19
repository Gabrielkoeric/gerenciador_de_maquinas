<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

use App\Repositories\ConfigGeral\ConfigGeralRepository;
use App\Repositories\Vm\VmRepository;
use App\Repositories\Cliente\ClienteRepository;
use App\Repositories\AsyncTasks\AsyncTasksRepository;

class LimpezaBkp extends Command
{
    protected $signature = 'limpeza:bkp';

    protected $description = 'Remove backups antigos conforme retenção configurada por cliente';

    protected ConfigGeralRepository $configRepo;
    protected VmRepository $vmRepository;
    protected ClienteRepository $clienteRepository;
    protected AsyncTasksRepository $asyncTasksRepository;

    public function __construct(
        ConfigGeralRepository $configRepo,
        VmRepository $vmRepository,
        ClienteRepository $clienteRepository,
        AsyncTasksRepository $asyncTasksRepository
    ) {
        parent::__construct();
        $this->configRepo = $configRepo;
        $this->vmRepository = $vmRepository;
        $this->clienteRepository = $clienteRepository;
        $this->asyncTasksRepository = $asyncTasksRepository;
    }

    public function handle()
    {
        $diasPadrao = $this->configRepo->getConfigGeral('diasRetencaoPadrao');
        $remote = $this->configRepo->getConfigGeral('remoteBkpBanco');
        $serverBkp = $this->configRepo->getConfigGeral('idServerBkpBanco');
        $dadosServerBkp = $this->vmRepository->getById($serverBkp);
        //$clientes = $this->clienteRepository->getClientesRetencaoBkp();

        $clientes = [
            (object) [
                'apelido' => 'teste',
                'diasRetencaoBkp' => null,
            ]
        ];

        $senhaSegura = escapeshellarg($dadosServerBkp->senha_local);
        
        $limpaLogsCommand = "rm -f /var/log/rclone/limpeza/*";
        
        $sshLimpaLogsCommand = "sshpass -p {$senhaSegura} ssh -o StrictHostKeyChecking=no "
            . "{$dadosServerBkp->usuario_local}@{$dadosServerBkp->ip_lan_vm} "
            . "\"{$limpaLogsCommand}\"";
        
        $processLimpaLogs = Process::fromShellCommandline($sshLimpaLogsCommand);
        $processLimpaLogs->setTimeout(null);
        $processLimpaLogs->run();

        foreach ($clientes as $cliente) {
            $dias = $cliente->diasRetencaoBkp ?? $diasPadrao;
            $apelido = $cliente->apelido;
            $cmd = "rclone delete {$remote}{$apelido}_escalasoft/ --min-age {$dias}d --log-file /var/log/rclone/limpeza/{$cliente->apelido}.log --log-level INFO --use-json-log";

            $taskId = $this->asyncTasksRepository->create(
                "Limpeza Bkp {$apelido}",
                [
                    'comando' => $cmd
                ]
            );

            Log::info("Task {$taskId} criada para limpeza de bkp: {$cmd}");

            Log::info("Comando Rclone montado para execução de limpeza de bkp: {$cmd}");

            $this->asyncTasksRepository->marcarComoIniciado($taskId);

            $senhaSegura = escapeshellarg($dadosServerBkp->senha_local);

            $sshCommand = "sshpass -p {$senhaSegura} ssh -o StrictHostKeyChecking=no "
                . "{$dadosServerBkp->usuario_local}@{$dadosServerBkp->ip_lan_vm} "
                . "\"{$cmd}\"";

            $process = Process::fromShellCommandline($sshCommand);
            $process->setTimeout(null);
            $process->run();

            if (!$process->isSuccessful()) {

                $this->asyncTasksRepository->marcarComoErro(
                    $taskId,
                    'Erro na execução da limpeza do backup.',
                    $cmd
                );

                Log::error(
                    "Erro ao executar limpeza do backup do cliente {$cliente->apelido}: "
                    . $process->getErrorOutput()
                );

                $this->error(
                    "Erro na limpeza do cliente {$cliente->apelido}"
                );

                continue;
            }

            $logCommand = "cat /var/log/rclone/limpeza/{$apelido}.log";

            $sshLogCommand = "sshpass -p {$senhaSegura} ssh -o StrictHostKeyChecking=no "
                . "{$dadosServerBkp->usuario_local}@{$dadosServerBkp->ip_lan_vm} "
                . "\"{$logCommand}\"";

            $logProcess = Process::fromShellCommandline($sshLogCommand);
            $logProcess->setTimeout(null);
            $logProcess->run();

            $logRclone = $logProcess->getOutput();

            $this->asyncTasksRepository->marcarComoConcluido(
                $taskId,
                $logRclone,
                $cmd
            );

            Log::info(
                "Limpeza de backup concluída para {$cliente->apelido}. "
                . "Retenção: {$dias} dias."
            );

            $this->info(
                "Backup {$cliente->apelido}: retenção {$dias} dias - OK"
            );
        }
        return Command::SUCCESS;
    }
}