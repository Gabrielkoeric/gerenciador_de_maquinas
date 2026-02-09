<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Vm\VmRepository;
use App\Repositories\ConfigGeral\ConfigGeralRepository;
use App\Repositories\Cliente\ClienteRepository;
use App\Repositories\VmServico\VmServicoRepository;
use App\Jobs\Sql\ExecutaSql;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use App\Jobs\Sql\ExecutaSqlServer;
use Illuminate\Support\Facades\DB;

class SyncDb extends Command
{
    protected $signature = 'sync:db';
    protected $description = 'Sincroniza bancos das VMs SGBD';

    protected ConfigGeralRepository $configRepo;
    protected VmRepository $vmRepository;
    protected ClienteRepository $clienteRepository;
    protected VmServicoRepository $vmServicoRepository;


    public function __construct(
        VmRepository $vmRepository,
        ConfigGeralRepository $configRepo,
        ClienteRepository $clienteRepository,
        VmServicoRepository $vmServicoRepository
    ) {
        parent::__construct();
        $this->vmRepository = $vmRepository;
        $this->configRepo = $configRepo;
        $this->clienteRepository = $clienteRepository;
        $this->vmServicoRepository = $vmServicoRepository;
    }

    public function handle()
    {
        // credenciais gerais
        $user = $this->configRepo->getConfigGeral('user_db');
        $pass = $this->configRepo->getConfigGeral('pass_db');

        // dados fixos
        $porta = 1433;
        $database = 'master';

        // busca VMs tipo SGBD
        $vms = $this->vmRepository->listarSgbd();

        foreach ($vms as $vm) {

            $dados = [
                'host'     => $vm->ip_lan,
                'user'     => $user,
                'password' => $pass,
                'port'     => $porta,
                'database' => $database,
            ];

            // SQL que será executado
            $sql = "
                    SELECT 
                        name,
                        state_desc
                    FROM sys.databases
                    WHERE state_desc = 'ONLINE'
                      AND name LIKE '%\_escalasoft' ESCAPE '\';


            ";

            $resultado = Bus::dispatchSync(
                new ExecutaSqlServer($dados, $sql)
            );

            Log::info('Resultado SQL', [
                'vm' => $vm->nome,
                'ip' => $vm->ip_lan,
                'resultado' => $resultado
            ]);

            if (!empty($resultado)){
                foreach ($resultado as $banco) {
                    // Acessa como array, não como objeto
                    $nomeBanco = $banco['name'] ?? null;

                    if (!$nomeBanco) {
                        Log::warning('Nome do banco não encontrado no resultado', [
                            'banco' => $banco
                        ]);
                        continue;
                    }

                    $apelido = str_replace('_escalasoft', '', $nomeBanco);

                    Log::info("apelido: $apelido");

                    $ultimoNumero = (int) substr($vm->nome, -2);

                    $tipoServico = ($ultimoNumero % 2 === 0)
                    ? 11 // secundário
                    : 10; // primário

                    $cliente = $this->clienteRepository->getClienteByApelido($apelido);

                    $vmServicoRepository = $this->vmServicoRepository->getByClienteAndServico($cliente->id_cliente_escala, $tipoServico);

                    if (!$vmServicoRepository) {
                        $this->vmServicoRepository->create([
                            'nome' => 'mssqlserver',
                            'porta' => 1433,
                            'status' => 'ONLINE',
                            'autostart' => 1,
                            'id_vm' => $vm->id_vm,
                            'id_servico' => $tipoServico,
                            'id_cliente_escala' => $cliente->id_cliente_escala,
                        ]);
                    }else{
                        $this->vmServicoRepository->updateVmByClienteAndServico(
                            $cliente->id_cliente_escala,
                            $tipoServico,
                            $vm->id_vm
                        );
                    }     
                }
            }
        }
        return Command::SUCCESS;
    }
}
