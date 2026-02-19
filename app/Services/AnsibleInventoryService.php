<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Repositories\Vm\VmRepository;

class AnsibleInventoryService
{
    protected VmRepository $vmRepository;

    public function __construct(VmRepository $vmRepository)
    {
        $this->vmRepository = $vmRepository;
    }

    public function gerarInventory(int $id_vm): string
    {
        $vm = $this->vmRepository->findByIdWithCredentials($id_vm);

        if (!empty($vm->id_dominio)) {
            $usuarioCompleto = "{$vm->dominio_usuario}@{$vm->dominio_nome}";
            $senha = $vm->dominio_senha;
            $transporte = "ntlm";
        } else {
            $usuarioCompleto = $vm->usuario_local;
            $senha = $vm->senha_local;
            $transporte = "basic";
        }

        $conteudo = <<<EOD
[windows]
{$vm->ip_lan}

[windows:vars]
ansible_user={$usuarioCompleto}
ansible_password={$senha}
ansible_port=5985
ansible_connection=winrm
ansible_winrm_transport={$transporte}
ansible_winrm_server_cert_validation=ignore
EOD;

        $hash = Str::uuid()->toString();
        $caminho = "ansible/inventories/{$hash}";

        Storage::disk('local')->makeDirectory('ansible/inventories');

        Storage::disk('local')->put($caminho, $conteudo);

        return $caminho;
    }

    public function removerInventory(string $caminho): void
    {
        Storage::disk('local')->delete($caminho);
    }
}
