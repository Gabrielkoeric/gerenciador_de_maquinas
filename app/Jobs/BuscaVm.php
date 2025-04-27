<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BuscaVm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $iplan;
    public $usuario;
    public $senha;
    public $dominio;
    public $taskId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($iplan, $usuario, $senha, $dominio = null, $taskId)
    {
        $this->iplan = $iplan;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->dominio = $dominio;
        $this->taskId = $taskId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
{
    DB::table('async_tasks')
            ->where('id_async_tasks', $this->taskId)
            ->update([
                'horario_inicio' => now(),
                'status' => 'executando'
            ]);

    $dir = storage_path('app/public/scripty');
    $hostsFile = $dir . '/hosts';

    if (!file_exists($dir)) {
        mkdir($dir, 0775, true);
        Log::info("Diretório {$dir} criado.");
    } else {
        Log::info("Diretório {$dir} já existe.");
    }

    // Verifica se a máquina está no domínio
    if (!empty($this->dominio)) {
        $usuarioCompleto = "{$this->usuario}@{$this->dominio}";
        $transporte = "ntlm";
    } else {
        $usuarioCompleto = $this->usuario;
        $transporte = "basic";
    }

    // Conteúdo a ser escrito no arquivo 'hosts'
    $conteudo = <<<EOD
    [windows]
    {$this->iplan}

    [windows:vars]
    ansible_user={$usuarioCompleto}
    ansible_password={$this->senha}
    ansible_port=5985
    ansible_connection=winrm
    ansible_winrm_transport={$transporte}
    ansible_winrm_server_cert_validation=ignore
    EOD;

    // Escreve o conteúdo no arquivo 'hosts'
    file_put_contents($hostsFile, $conteudo);
    Log::info("Arquivo {$hostsFile} atualizado com sucesso.");

    $playbookName = 'listar_vms_hyperv.yml';
     
    $playbook = $dir . '/' . $playbookName;
    Log::info("Playbook selecionado: {$playbook}");

    if (!file_exists($playbook)) {
        Log::error("Playbook não encontrado: {$playbook}");
        return "Playbook não encontrado: {$playbook}";
    }

    $comando = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i " . escapeshellarg($hostsFile) .
           " " . escapeshellarg($playbook);

    Log::info("Comando montado para execução: {$comando}");

    // Executa o comando
    $output = shell_exec($comando);
       
    // Registra a saída do comando
    Log::info("Saída do comando: {$output}");
    DB::table('async_tasks')
            ->where('id_async_tasks', $this->taskId)
            ->update([
                'horario_fim' => now(),
                'status' => 'finalizado',
                'log' => $output
            ]);
}
}
