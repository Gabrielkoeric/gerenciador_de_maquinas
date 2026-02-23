<?php

namespace App\Jobs\ManipulaUsuario;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Repositories\AsyncTasks\AsyncTasksRepository;

use App\Services\AnsibleInventoryService;

class Create implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $usuario;
    protected string $senha;
    protected string $coletor;
    protected string $arquivo;
    protected string $taskId;
    protected string $apelido;

    public function __construct(string $usuario, string $senha, string $coletor, string $arquivo, string $taskId, string $apelido)
    {
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->coletor = $coletor;
        $this->arquivo = $arquivo;
        $this->taskId = $taskId;
        $this->apelido = $apelido;
    }

    public function handle(
        AnsibleInventoryService $inventoryService,
        AsyncTasksRepository $asyncTasksRepository
        )
    {
        try {
            $asyncTasksRepository->marcarComoIniciado($this->taskId);

            $dir = base_path('scriptyAnsible/manipulaUsuarios');
            $playbook = $dir . '/creatUsuario.yml';

            $inventoryPath = storage_path('app/' . $this->arquivo);

            $usuario = explode('@', $this->usuario)[0];
    
            $extraVars = [
                "usuario" => $usuario,
                "pass"    => $this->senha,
                "ou"      => $this->apelido
            ];

            if ((int)$this->coletor === 1) {
                $extraVars["ou1"] = "coletor";
            }

            $extraVarsJson = json_encode($extraVars);

            $comando = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i "
                . escapeshellarg($inventoryPath)
                . " "
                . escapeshellarg($playbook)
                . " --extra-vars "
                . escapeshellarg($extraVarsJson);

            $output = shell_exec($comando);

            Log::info("$comando");

            Log::info("$output");

            Log::info("Reset usuário executado com sucesso");

        } finally {
            $inventoryService->removerInventory($this->arquivo);
            Log::info("Inventory removido", ['path' => $this->arquivo]);
        }
        $asyncTasksRepository->marcarComoConcluido($this->taskId, $output, $comando);
    }
}
