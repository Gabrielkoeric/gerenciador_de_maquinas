<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\AlertaTelegram;
use Illuminate\Support\Facades\Notification;
use App\Jobs\Notificacao\Telegram;

class ManipulaServicoWindows implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $dados;
    public $acao;
    public $taskId;
    public $usuarioLogado;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dados, $acao, $taskId, $usuarioLogado)
    {
        $this->dados = $dados;
        $this->acao = $acao;
        $this->taskId = $taskId;
        $this->usuarioLogado = $usuarioLogado;
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

        $dir = base_path('scriptyAnsible/vm');

        $hostsFile = $dir . '/hosts';

        // Verifica se a máquina está no domínio
        if (!empty($this->dados->id_dominio)) {
            $usuarioCompleto = "{$this->dados->dominio_usuario}@{$this->dados->dominio_nome}";
            $senha = $this->dados->dominio_senha;
            $transporte = "ntlm";
        } else {
            $usuarioCompleto = $this->dados->usuario_local;
            $senha = $this->dados->senha_local;
            $transporte = "basic";
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

        // Escreve o conteúdo no arquivo 'hosts'
        file_put_contents($hostsFile, $conteudo);
        // Define o nome do playbook com base na ação
        switch (strtolower($this->acao)) {
            case 'start':
                $playbookName = 'inicia_servico.yml';
                break;
            case 'stop':
                $playbookName = 'para_servico.yml';
                break;
            case 'restart':
                $playbookName = 'reinicia_servico.yml';
                break;
            case 'status':
                $playbookName = 'status_servico.yml';
                break;
        }
        $playbook = $dir . '/' . $playbookName;

        $comando = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i " . escapeshellarg($hostsFile) .
           " " . escapeshellarg($playbook) .
           " --extra-vars " . escapeshellarg("servico={$this->dados->nome}");

        $output = shell_exec($comando);

        Log::info("id job {$this->taskId}, ação {$this->acao}, serviço nome {$this->dados->nome}");

        $nome = str_replace('_', '', $this->dados->nome);

        //Notification::route('telegram', 5779378630)->notify(new AlertaTelegram("✅ Job finalizado: {$this->taskId} Ação: {$this->acao} Serviço: {$nome} "));

        Telegram::dispatch("✅ Job finalizado:{$this->taskId} Ação:{$this->acao} Serviço:{$nome}");
       
        $estado = null;
        //captura o status
        if (preg_match('/"state"\s*:\s*"([^"]+)"/', $output, $matches)) {
            $estado = $matches[1]; // Vai capturar, por exemplo, "started"
        }
            
        if ($estado) {
            DB::table('servico_vm')
                ->where('id_servico_vm', $this->dados->id_servico_vm)
                ->update([
                    'status' => $estado,
                    'updated_at' => now(),
                ]);
            }
        
         ////gravar log
        $status = 'sucesso';
        if (
            str_contains($output, 'unreachable=1') ||
            str_contains($output, 'failed=1') ||
            str_contains($output, 'UNREACHABLE') ||
            str_contains($output, 'FAILED') ||
            empty($output)
        ) {
            $status = 'falha';
        }
     
        $erro = null;
        if ($status === 'falha') {
            if (preg_match('/"msg"\s*:\s*"([^"]+)"/', $output, $matches)) {
                $erro = $matches[1];
            } elseif (preg_match('/msg=(.*)/', $output, $matches)) {
                $erro = $matches[1];
            } else {
                $erro = 'Erro desconhecido';
            }
        }
     
        DB::table('logs_execucoes')->insert([
            'acao'          => $this->acao,
            'playbook'      => $playbookName ?? null,
            'comando'       => $comando ?? null,
            'saida'         => $output ?? null,
            'status'        => $status,
            'erro'          => $erro,
            'executado_em'  => now(),
            'created_at'    => now(),
            'updated_at'    => now(),
            'id'            => $this->usuarioLogado,
            'id_servico_vm' => $this->dados->id_servico_vm,
     ]);
     ///////////////////


        
        DB::table('async_tasks')
            ->where('id_async_tasks', $this->taskId)
            ->update([
                'horario_fim' => now(),
                'status' => 'Concluido',
                'log' => $output
        ]);
    }
            
}
