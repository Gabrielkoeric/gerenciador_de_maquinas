<?php

namespace App\Jobs\ManipulaUsuario;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

use App\Services\AnsibleInventoryService;

class Reset implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $usuario;
    protected string $novaSenha;
    protected string $arquivo;

    public function __construct(string $usuario, string $novaSenha, string $arquivo)
    {
        $this->usuario = $usuario;
        $this->novaSenha = $novaSenha;
        $this->arquivo = $arquivo;
    }

    public function handle(AnsibleInventoryService $inventoryService)
    {
        try {
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
    }
}
