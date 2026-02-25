<?php

namespace App\Services\SecaoCloudLoteService;

use Illuminate\Support\Facades\DB;

use App\Repositories\Cliente\ClienteRepository;
use App\Repositories\SecoesCloud\SecoesCloudRepository;
use App\Repositories\ConfigGeral\ConfigGeralRepository;
use App\Repositories\AsyncTasks\AsyncTasksRepository;

use App\Services\AnsibleInventoryService;

use App\Jobs\ManipulaUsuario\Create;
use App\Jobs\ManipulaUsuario\Delete;

class SecaoCloudLoteService
{
    protected ClienteRepository $cliente;
    protected SecoesCloudRepository $secaoCloud;
    protected ConfigGeralRepository $configRepo;
    protected AsyncTasksRepository $asyncTasksRepository;
    protected AnsibleInventoryService $inventoryService;

    public function __construct(
        ClienteRepository $cliente,
        SecoesCloudRepository $secaoCloud,
        ConfigGeralRepository $configRepo,
        AsyncTasksRepository $asyncTasksRepository,
        AnsibleInventoryService $inventoryService
    ) {
        $this->cliente = $cliente;
        $this->secaoCloud = $secaoCloud;
        $this->configRepo = $configRepo;
        $this->asyncTasksRepository = $asyncTasksRepository;
        $this->inventoryService = $inventoryService;
    }

    public function processar(int $idCliente, int $novoColetor, int $novoDesktop): void
    {
        $usuariosAtuais = $this->secaoCloud->countUsuariosSecaoCloud($idCliente);

        $diffColetor = $novoColetor - $usuariosAtuais['coletor'];
        $diffDesktop = $novoDesktop - $usuariosAtuais['desktop'];

        $id_vm = $this->configRepo->getConfigGeral('id_ad_clientes');
        $apelido = $this->cliente -> findById($idCliente)->apelido;

        if ($diffColetor > 0) {
            for ($i = 0; $i < $diffColetor; $i++) {
                
                $arquivo = $this->inventoryService->gerarInventory($id_vm);

                $numeroBase = $usuariosAtuais['coletor'];

                $sequencial = $numeroBase + $i + 1;

                $usuario = sprintf(
                    '%s.colet%02d@cloud.escalasoft.com.br',
                    $apelido,
                    $sequencial
                );

                $senha = substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*', 8)), 0, 8);
                $coletor = 1;

                $dado = [
                    'usuario' => $usuario,
                    'senha' => $senha,
                    'id_cliente_escala' => $idCliente,
                    'coletor' => $coletor
                ];

                $taskId = $this->asyncTasksRepository->create('Create User', $dado);

                Create::dispatch($usuario, $senha, $coletor, $arquivo, $taskId, $apelido);

                DB::table('secao_cloud')->insertGetId($dado);
            }
        }

            if ($diffColetor < 0) {
                for ($i = 0; $i < abs($diffColetor); $i++) {
                
                    $arquivo = $this->inventoryService->gerarInventory($id_vm);

                    $ultimo = $this->secaoCloud->getUltimoUsuario($idCliente, 1);
                    $usuario = $ultimo->usuario;

                    $this->secaoCloud->deleteById($ultimo->id_secao_cloud);

                    $dado = [
                        'id_vm' => $id_vm,
                        'usuario' => $usuario,
                        'arquivo' => $arquivo
                    ];

                    $taskId = $this->asyncTasksRepository->create('Delete User', $dado);

                    Delete::dispatch($usuario, $arquivo, $taskId);
                }
            }

            if ($diffDesktop > 0) {
                for ($i = 0; $i < $diffDesktop; $i++) {

                    $arquivo = $this->inventoryService->gerarInventory($id_vm);

                    $numeroBase = $usuariosAtuais['desktop'];

                    $sequencial = $numeroBase + $i + 1;

                    $usuario = sprintf(
                        '%s%02d@cloud.escalasoft.com.br',
                        $apelido,
                        $sequencial
                    );

                    $senha = substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*', 8)), 0, 8);
                    $coletor = 0;

                    $dado = [
                        'usuario' => $usuario,
                        'senha' => $senha,
                        'id_cliente_escala' => $idCliente,
                        'coletor' => $coletor
                    ];

                    $taskId = $this->asyncTasksRepository->create('Create User', $dado);

                    Create::dispatch($usuario, $senha, $coletor, $arquivo, $taskId, $apelido);

                    DB::table('secao_cloud')->insertGetId($dado);
                }
            }

            if ($diffDesktop < 0) {
                for ($i = 0; $i < abs($diffDesktop); $i++) {

                    $arquivo = $this->inventoryService->gerarInventory($id_vm);

                    $ultimo = $this->secaoCloud->getUltimoUsuario($idCliente, 0);
                    $usuario = $ultimo->usuario;

                    $this->secaoCloud->deleteById($ultimo->id_secao_cloud);

                    $dado = [
                        'id_vm' => $id_vm,
                        'usuario' => $usuario,
                        'arquivo' => $arquivo
                    ];

                    $taskId = $this->asyncTasksRepository->create('Delete User', $dado);

                    Delete::dispatch($usuario, $arquivo, $taskId);
                }
            }
    }
}
