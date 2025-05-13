<?php

namespace App\Jobs\Vm;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Notifications\AlertaTelegram;
use Illuminate\Support\Facades\Notification;
use App\Jobs\Notificacao\Telegram;

class StartVm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $idServidorFisico;
    public $taskId;
    public $nome;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($idServidorFisico, $taskId, $nome)
    {
        $this->idServidorFisico = $idServidorFisico;
        $this->taskId = $taskId;
        $this->nome = $nome;
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

        $server = DB::table('servidor_fisico as sf')
                ->leftJoin('dominio as d', 'sf.id_dominio', '=', 'd.id_dominio')
                ->leftJoin('usuario_servidor_fisico as usf', function ($join) {
                    $join->on('usf.id_servidor_fisico', '=', 'sf.id_servidor_fisico')
                    ->where('usf.principal', '=', 1);
                })
                ->leftJoin('ip_lan as il', 'sf.id_ip_lan', '=', 'il.id_ip_lan')
                ->leftJoin('ip_wan as iw', 'sf.id_ip_wan', '=', 'iw.id_ip_wan')
                ->select(
                    'sf.*',
                    'd.nome as dominio_nome',
                    'd.usuario as dominio_usuario',
                    'd.senha as dominio_senha',
                    'usf.usuario as usuario_servidor',
                    'usf.senha as senha_servidor',
                    'il.ip as ip_lan',
                    'iw.ip as ip_wan'
                )
                ->where('sf.id_servidor_fisico', $this->idServidorFisico)
                ->first();

                $dir = base_path('scriptyAnsible/vm');
                $hostsFile = $dir . '/hosts';
        
                // Verifica se a máquina está no domínio
                if (!empty($server->dominio_nome)) {
                    $usuarioCompleto = "{$server->dominio_usuario}@{$server->dominio_nome}";
                    $senha = $server->dominio_senha;
                    $transporte = "ntlm";
                } else {
                    $usuarioCompleto = $server->usuario_servidor;
                    $senha = $server->senha_servidor;
                    $transporte = "basic";
                }
                
      // Conteúdo a ser escrito no arquivo 'hosts'
        $conteudo = <<<EOD
        [windows]
        {{$server->ip_lan}}

        [windows:vars]
        ansible_user={$usuarioCompleto}
        ansible_password={$senha}
        ansible_port=5985
        ansible_connection=winrm
        ansible_winrm_transport={$transporte}
        ansible_winrm_server_cert_validation=ignore
        EOD;

        file_put_contents($hostsFile, $conteudo);

        $playbookName = 'StartVm.yml';
     
        $playbook = $dir . '/' . $playbookName;

        $comando = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i " . escapeshellarg($hostsFile) .
           " " . escapeshellarg($playbook) .
           " -e " . escapeshellarg("nome_vm={$this->nome}");


    $output = shell_exec($comando);

    //Notification::route('telegram', 5779378630)->notify(new AlertaTelegram("✅ Job finalizado: {$this->taskId}Start na VM{$this->dados->nome} "));

    Telegram::dispatch("✅ Job finalizado: {$this->taskId}Start na VM{$this->dados->nome}");

    DB::table('async_tasks')
            ->where('id_async_tasks', $this->taskId)
            ->update([
                'horario_fim' => now(),
                'status' => 'Concluido',
                'log' => $output
            ]);
    }
}
