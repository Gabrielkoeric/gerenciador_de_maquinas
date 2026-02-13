<?php

namespace App\Jobs\Deploy;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Swarm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $clienteDados;
    public $dados;
    public $escalaServer;
    public $nome_servico;
    public $taskId;

    public function __construct($clienteDados, $dados, $escalaServer, $nome_servico, $taskId)
    {
        $this->clienteDados = $clienteDados;
        $this->dados = $dados;
        $this->escalaServer = $escalaServer;
        $this->nome_servico = $nome_servico;
        $this->taskId = $taskId;
    }

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

        $playbookName = 'EscalaSwarm.yml';
     
        $playbook = $dir . '/' . $playbookName;
        
        $nome_servico = $this->nome_servico;
        $porta = $this->escalaServer->porta;
        $nome_vm_aplicacao = $this->escalaServer->nome_vm;
        $server = $nome_vm_aplicacao . ':' . $porta;
        $apelido = $this->clienteDados->apelido;
        $sistema = $apelido . "_escalasoft";

        $comando = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i " . escapeshellarg($hostsFile) .
           " " . escapeshellarg($playbook) .
           " --extra-vars " . escapeshellarg("nomeservico=$nome_servico sistema=$sistema server=$server apelido=$apelido");

        $output = shell_exec($comando);

        if (str_contains($output, 'failed=0') && str_contains($output, 'fatal:') === false) {
            // Sucesso: serviço foi instalado corretamente
            DB::table('servico_vm')->insert([
                'nome' => $nome_servico,
                'porta' => '0',
                'autostart' => 1,
                'id_vm' => $this->dados->id_vm,
                'id_servico' => '1',
                'id_cliente_escala' => $this->clienteDados->id_cliente_escala,
            ]);
        }

        DB::table('async_tasks')
            ->where('id_async_tasks', $this->taskId)
            ->update([
                'horario_fim' => now(),
                'status' => 'Concluido',
                'log' => $output
            ]);
    
    }
}
