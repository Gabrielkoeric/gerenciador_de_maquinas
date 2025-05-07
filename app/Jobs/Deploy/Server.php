<?php

namespace App\Jobs\Deploy;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Server implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ultimaPorta;
    public $clienteApelido;
    public $vm;
    public $taskId;
    public $dados;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ultimaPorta, $clienteApelido, $vm, $taskId, $dados)
    {
        $this->ultimaPorta = $ultimaPorta;
        $this->clienteApelido = $clienteApelido;
        $this->vm = $vm;
        $this->taskId = $taskId;
        $this->dados = $dados;
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

        $dir = base_path('scriptyAnsible/deploy');
        $hostsFile = $dir . '/hosts';

        if (!empty($this->dados->dominio_nome)) {
            $usuarioCompleto = "{$this->dados->dominio_usuario}@{$this->dados->dominio_nome}";
            $senha = $this->dados->dominio_senha;
            $transporte = "ntlm";
        } else {
            $usuarioCompleto = $this->dados->usuario_local;
            $transporte = "basic";
            $senha = $this->dados->senha_local;
        }

        // Conteúdo a ser escrito no arquivo 'hosts'
        $conteudo = <<<EOD
        [windows]
        {$this->dados->ip_lan_vm}

        [windows:vars]
        ansible_user={$usuarioCompleto}
        ansible_password={$senha}
        ansible_port=5985
        ansible_connection=winrm
        ansible_winrm_transport={$transporte}
        ansible_winrm_server_cert_validation=ignore
        EOD;

        file_put_contents($hostsFile, $conteudo);

        $playbookName = 'EscalaServer.yml';
     
        $playbook = $dir . '/' . $playbookName;

        $porta = $this->ultimaPorta + 1;

        $comando = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i " . escapeshellarg($hostsFile) .
           " " . escapeshellarg($playbook) .
           " --extra-vars " . escapeshellarg("cliente=$this->clienteApelido porta=$porta");

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
