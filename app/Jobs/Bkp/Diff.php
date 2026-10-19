<?php

namespace App\Jobs\Bkp;

use App\Services\Ssh\SshService;
use App\Repositories\ConfigGeral\ConfigGeralRepository;
use App\Repositories\Vm\VmRepository;
use App\Repositories\Cliente\ClienteRepository;
use App\Repositories\AsyncTasks\AsyncTasksRepository;
use App\Repositories\Bkp\BkpRepository;
use Illuminate\Support\Facades\Log as LaravelLog;
use Carbon\Carbon;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class Diff implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public function handle(
        ConfigGeralRepository $configRepo,
        VmRepository $vmRepository,
        ClienteRepository $clienteRepository,
        AsyncTasksRepository $asyncTasksRepository,
        BkpRepository $bkpRepository,
        SshService $sshService
    ) {
        $remote = $configRepo->getConfigGeral('remoteBkpBanco');
        $serverBkp = $configRepo->getConfigGeral('idServerBkpBanco');
        $dadosServerBkp = $vmRepository->getById($serverBkp);
        //$clientes = $clienteRepository->getClientesBkp();

        $clientes = collect([
            (object) ['apelido' => 'teste'],
        ]);
        
        $resultadoLimpeza = $sshService->limpaPasta(
            $dadosServerBkp->ip_lan_vm,
            $dadosServerBkp->usuario_local,
            $dadosServerBkp->senha_local,
            '/var/log/rclone/diff/'
        );

        if (!$resultadoLimpeza['sucesso']) {
            throw new \RuntimeException(
                'Falha ao limpar a pasta de logs: ' . $resultadoLimpeza['erro']
            );
        }

        foreach ($clientes as $cliente) {
            $apelido = $cliente->apelido;

            $origem = "/mnt/backup/clientes/{$apelido}_escalasoft/diff/";
            $destino = "{$remote}{$apelido}_escalasoft/diff/";
            $logPath = "/var/log/rclone/diff/{$apelido}.log";

            $cmd = "rclone copy "
                . escapeshellarg($origem) . " "
                . escapeshellarg($destino)
                . " --progress --checkers=32 --transfers=10"
                . " --log-file " . escapeshellarg($logPath)
                . " --log-level INFO --use-json-log";

            $taskId = $asyncTasksRepository->create(
                "Envio de backup Diff {$apelido}",
                [
                    'comando' => $cmd,
                ]
            );

            $asyncTasksRepository->marcarComoIniciado($taskId);

            $logRclone = '';
            $erroExecucao = null;

            try {
                $resultado = $sshService->executarComando(
                    $dadosServerBkp->ip_lan_vm,
                    $dadosServerBkp->usuario_local,
                    $dadosServerBkp->senha_local,
                    $cmd,
                    null
                );

                if (!$resultado['sucesso']) {
                    $erroExecucao = "Falha na execução do Rclone. "
                        . "Código de saída: {$resultado['codigo_saida']}. "
                        . $resultado['erro'];
                }
            } catch (\Throwable $e) {
                $erroExecucao = "Erro ao executar o comando via SSH: "
                    . $e->getMessage();
            }

            try {
                $resultadoLog = $sshService->lerArquivoLog(
                    $dadosServerBkp->ip_lan_vm,
                    $dadosServerBkp->usuario_local,
                    $dadosServerBkp->senha_local,
                    $logPath
                );

                if ($resultadoLog['sucesso']) {
                    $logRclone = $resultadoLog['saida'];

                    foreach (explode("\n", $logRclone) as $linha) {
                        $registro = json_decode($linha, true);

                        if (
                            !is_array($registro)
                            || ($registro['msg'] ?? null) !== 'Copied (new)'
                            || empty($registro['object'])
                        ) {
                            continue;
                        }

                        $nomeArquivo = basename($registro['object']);

                        // Extrai data e hora do nome do arquivo.
                        if (!preg_match(
                            '/_(\d{8})_(\d{4})\.[^.]+$/',
                            $nomeArquivo,
                            $matches
                        )) {
                            continue;
                        }

                        $dataBackup = Carbon::createFromFormat(
                            '!Ymd_Hi',
                            $matches[1] . '_' . $matches[2]
                        );

                        $cliente = $clienteRepository->getClienteByApelido($apelido);

                        if (!$cliente) {
                            LaravelLog::warning(
                                "Cliente não encontrado para cadastrar backup: {$apelido}"
                            );

                            continue;
                        }

                        $idCliente = $cliente->id_cliente_escala;

                        $bkpRepository->inserirSeNaoExistir([
                            'objeto' => "diff/{$nomeArquivo}",
                            'data_backup' => $dataBackup,
                            'tipo' => 'diff',
                            'tamanho_bytes' => 0,
                            'id_cliente_escala' => $idCliente,
                        ]);
                    }

                } else {
                    $logRclone = "Falha ao coletar o arquivo de log: "
                        . $resultadoLog['erro'];
                }
            } catch (\Throwable $e) {
                $logRclone = "Erro ao coletar o arquivo de log via SSH: "
                    . $e->getMessage();
            }

            if ($erroExecucao !== null) {
                $logRclone .= "\n\nResultado da execução:\n"
                    . $erroExecucao;
            }

            if (trim($logRclone) === '') {
                $logRclone = 'O comando foi finalizado, mas não foi possível '
                    . 'obter conteúdo do arquivo de log.';
            }

            $asyncTasksRepository->marcarComoConcluido(
                $taskId,
                $logRclone,
                $cmd
            );

            if ($erroExecucao !== null) {
                LaravelLog::warning(
                    "Envio de backup DIFF finalizado com erro para {$apelido}. "
                    . $erroExecucao
                );
            } else {
                LaravelLog::info(
                    "Envio de backup LOG finalizado para {$apelido}."
                );
            }
        }
    }
}