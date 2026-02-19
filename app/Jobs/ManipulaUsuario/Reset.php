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

class Reset implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $usuario;
    protected string $novaSenha;
    protected string $arquivo;
    protected string $taskId;

    public function __construct(string $usuario, string $novaSenha, string $arquivo, string $taskId)
    {
        $this->usuario = $usuario;
        $this->novaSenha = $novaSenha;
        $this->arquivo = $arquivo;
        $this->taskId = $taskId;
    }

    public function handle(
        AnsibleInventoryService $inventoryService,
        AsyncTasksRepository $asyncTasksRepository
        )
    {
        try {
            $asyncTasksRepository->marcarComoIniciado($this->taskId);

            $dir = base_path('scriptyAnsible/manipulaUsuarios');
            $playbook = $dir . '/resetUsuario.yml';

            $inventoryPath = storage_path('app/' . $this->arquivo);

            $comando = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i " . escapeshellarg($inventoryPath) .
           " " . escapeshellarg($playbook) .
           " --extra-vars " . escapeshellarg("usuario={$this->usuario} senha={$this->novaSenha}");

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
