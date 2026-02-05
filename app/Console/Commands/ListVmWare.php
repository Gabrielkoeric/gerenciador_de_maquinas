<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\ConfigGeral\ConfigGeralRepository;
use App\Repositories\Acessos\AcessosRepository;
use App\Repositories\Vm\VmRepository;
use App\Jobs\Notificacao\Telegram;

use Illuminate\Support\Facades\Log;

class ListVmWare extends Command
{
    protected $signature = 'list:vmware';
    protected $description = 'Command description';

    protected ConfigGeralRepository $configRepo;
    protected AcessosRepository $acessosRepository;
    protected VmRepository $vmRepository;

    public function __construct(
        ConfigGeralRepository $configRepo,
        AcessosRepository $acessosRepository,
        VmRepository $vmRepository
    ) {
        parent::__construct();
        $this->configRepo = $configRepo;
        $this->acessosRepository = $acessosRepository;
        $this->vmRepository = $vmRepository;
    }

    public function handle()
    {
        $vcenterUrl = $this->configRepo->getConfigGeral('vcenterUrl');
        $idacessovmware = $this->configRepo->getConfigGeral('idacessovmware');

        $dados = $this->acessosRepository->getAcesso($idacessovmware);

        $user = $dados->usuario;
        $pass = $dados->senha;

        Log::info("User: $user e Pass: $pass");

        try {
            $authResponse = Http::withOptions([
                'verify' => false
            ])
            ->withBasicAuth($user, $pass)
            ->post($vcenterUrl . '/rest/com/vmware/cis/session');

        if (!$authResponse->successful()) {
            Log::error('Erro ao autenticar no vCenter', [
                'status' => $authResponse->status(),
                'body'   => $authResponse->body()
            ]);

            abort(500, 'Erro ao autenticar no vCenter');
        }

        $sessionId = $authResponse->json('value');

        Log::info('Sessão vCenter criada', [
            'session_id' => $sessionId
        ]);

        $vmResponse = Http::withOptions([
                'verify' => false
            ])
            ->withHeaders([
                'Cookie' => 'vmware-api-session-id=' . $sessionId
            ])
            ->get($vcenterUrl . '/api/vcenter/vm');

        if (!$vmResponse->successful()) {
            Log::error('Erro ao listar VMs do vCenter', [
                'status' => $vmResponse->status(),
                'body'   => $vmResponse->body()
            ]);

            abort(500, 'Erro ao listar VMs do vCenter');
        }

        $vmGerenciador = $this->vmRepository->getVms();

        $vms = $vmResponse->json();

        Log::info('Lista de VMs retornada pelo vCenter - nomes');

        foreach ($vms as $vm) {
            Log::info('VM encontrada', [
                'name' => $vm['name'] ?? 'SEM_NOME'
            ]);
        }

        $vmsVmware = collect($vms)
            ->pluck('name')
            ->filter()       
            ->values()
            ->toArray();

        $vmsGerenciador = $vmGerenciador
            ->pluck('nome')
            ->filter()
            ->values()
            ->toArray();

        $soNoGerenciador = array_diff($vmsGerenciador, $vmsVmware);

        $soNoVmware = array_diff($vmsVmware, $vmsGerenciador);

        if (!empty($soNoGerenciador)) {
            Telegram::dispatch("🚨 VMs no GERENCIADOR mas NÃO existem no VMware:");
            foreach ($soNoGerenciador as $nome) {
                Telegram::dispatch("❌ {$nome}");
            }
        }

        if (!empty($soNoVmware)) {
            Telegram::dispatch("🚨 VMs no VMWARE mas NÃO estão cadastradas no Gerenciador:");
            foreach ($soNoVmware as $nome) {
                Telegram::dispatch("⚠️ {$nome}");
            }
        }

        } catch (\Throwable $e) {
            Log::error('Erro inesperado ao consultar vCenter', [
                'exception' => $e->getMessage()
            ]);

            abort(500, 'Erro interno');
        }
        return Command::SUCCESS;
    }
}
