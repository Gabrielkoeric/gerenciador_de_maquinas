<?php

namespace App\Jobs\Server;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ListaVmServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $dados;
    public $taskId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dados, $taskId)
    {
        $this->dados = $dados;
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
                'status' => 'Iniciado'
            ]);

        $dir = base_path('scriptyAnsible/server');
        $hostsFile = $dir . '/hosts';

        // Verifica se a máquina está no domínio
        if (!empty($this->dados->dominio_nome)) {
            $usuarioCompleto = "{$this->dados->dominio_usuario}@{$this->dados->dominio_nome}";
            $senha = $this->dados->dominio_senha;
            $transporte = "ntlm";
        } else {
            $usuarioCompleto = $this->dados->usuario_servidor;
            $transporte = "basic";
            $senha = $this->dados->senha_servidor;
        }

    // Conteúdo a ser escrito no arquivo 'hosts'
    $conteudo = <<<EOD
    [windows]
    {$this->dados->ip_lan}

    [windows:vars]
    ansible_user={$usuarioCompleto}
    ansible_password={$senha}
    ansible_port=5985
    ansible_connection=winrm
    ansible_winrm_transport={$transporte}
    ansible_winrm_server_cert_validation=ignore
    EOD;

    file_put_contents($hostsFile, $conteudo);

    $playbookName = 'ListaVmServer.yml';
     
    $playbook = $dir . '/' . $playbookName;

    $comando = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i " . escapeshellarg($hostsFile) .
           " " . escapeshellarg($playbook);

    $output = shell_exec($comando);

        DB::table('async_tasks')
            ->where('id_async_tasks', $this->taskId)
            ->update([
                'horario_fim' => now(),
                'status' => 'Concluido',
                'log' => $output
            ]);
    }

}