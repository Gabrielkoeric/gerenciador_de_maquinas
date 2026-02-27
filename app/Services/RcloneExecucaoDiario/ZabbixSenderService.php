<?php

namespace App\Services\RcloneExecucaoDiario;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

use App\Repositories\ConfigGeral\ConfigGeralRepository;
use App\Repositories\Vm\VmRepository;

class ZabbixSenderService
{
    protected ConfigGeralRepository $configRepo;
    protected int $zbxPort = 10051;
    protected VmRepository $vmRepository;

    public function __construct(
        ConfigGeralRepository $configRepo,
        VmRepository $vmRepository
    ) {
        $this->configRepo = $configRepo;
        $this->vmRepository = $vmRepository;
    }

    public function send(string $host, string $key, $value): void
    {
        $this->sendBatch($host, [$key => $value]);
    }

    protected function sendBatch(string $host, array $items): void
    {
        $id_zbx = $this->configRepo->getConfigGeral('id_zbx');

        $vm = $this->vmRepository->getById((int) $id_zbx);

        $zbxHost = $vm->ip_lan_vm;

        Log::info("zbxHost $zbxHost");

        foreach ($items as $key => $value) {

            $command = [
                'zabbix_sender',
                '-z', $zbxHost,
                '-p', $this->zbxPort,
                '-s', $host,
                '-k', $key,
                '-o', $value
            ];

            $process = new Process($command);
            $process->setTimeout(5);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('Erro ao enviar métrica para Zabbix', [
                    'host' => $host,
                    'key' => $key,
                    'value' => $value,
                    'error' => $process->getErrorOutput()
                ]);
            } else {
                Log::info('Métrica enviada para Zabbix', [
                    'host' => $host,
                    'key' => $key,
                    'value' => $value
                ]);
            }
        }
    }
}