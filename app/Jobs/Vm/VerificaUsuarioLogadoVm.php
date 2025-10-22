<?php

namespace App\Jobs\Vm;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VerificaUsuarioLogadoVm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $vms;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($vms)
    {
        $this->vms = $vms;
    }
   
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->vms as $vm) {
            $dados = DB::table('vm')
            ->select(
                'vm.*',
                'ip_lan.ip as ip_lan_vm',
                'dominio.nome as dominio_nome',
                'dominio.usuario as dominio_usuario',
                'dominio.senha as dominio_senha',
                'servidor_fisico.nome as nome_servidor_fisico',
                'usuario_vm.usuario as usuario_local',
                'usuario_vm.senha as senha_local'
            )
            ->leftJoin('ip_lan', 'vm.id_ip_lan', '=', 'ip_lan.id_ip_lan')
            ->leftJoin('dominio', 'vm.id_dominio', '=', 'dominio.id_dominio')
            ->leftJoin('servidor_fisico', 'vm.id_servidor_fisico', '=', 'servidor_fisico.id_servidor_fisico')
            ->leftJoin('usuario_vm', function ($join) {
                $join->on('vm.id_vm', '=', 'usuario_vm.id_vm')
                 ->where('usuario_vm.principal', '=', 1);
            })
            ->where('vm.id_vm', '=', $vm)
            ->first();

            $dir = base_path('scriptyAnsible/vm');
            $hostsFile = $dir . '/hosts';

            // Verifica se a máquina está no domínio
            if (!empty($dados->dominio_nome)) {
                $usuarioCompleto = "{$dados->dominio_usuario}@{$dados->dominio_nome}";
                $senha = $dados->dominio_senha;
                $transporte = "ntlm";
            } else {
                $usuarioCompleto = $dados->usuario_local;
                $transporte = "basic";
                $senha = $dados->senha_local;
            }

            // Conteúdo a ser escrito no arquivo 'hosts'
            $conteudo = <<<EOD
            [windows]
            {$dados->ip_lan_vm}

            [windows:vars]
            ansible_user={$usuarioCompleto}
            ansible_password={$senha}
            ansible_port=5985
            ansible_connection=winrm
            ansible_winrm_transport={$transporte}
            ansible_winrm_server_cert_validation=ignore
            EOD;

            file_put_contents($hostsFile, $conteudo);

            $playbookName = 'VerificaUsuarioLogadoVm.yml';
            
            $playbook = $dir . '/' . $playbookName;

            $comando = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i " . escapeshellarg($hostsFile) .
                   " " . escapeshellarg($playbook);

            $output = shell_exec($comando);

            // $output contém o JSON retornado do playbook
            Log::info("Resultado do playbook VerificaUsuarioLogadoVm para VM {$vm}:\n" . $output);

            try {
                // Extrai o conteúdo JSON do output do ansible
                if (preg_match('/\{[\s\S]*\}/', $output, $matches)) {
                    $jsonStr = $matches[0];
                    $dadosJson = json_decode($jsonStr, true);
                } else {
                    Log::warning("Nenhum JSON encontrado no output da VM {$vm}");
                    continue;
                }

                if (!isset($dadosJson['msg'])) {
                    Log::warning("Estrutura JSON inesperada para VM {$vm}");
                    continue;
                }

                // Pode ser objeto único ou array de objetos
                $listaUsuarios = is_array($dadosJson['msg']) && isset($dadosJson['msg'][0])
                    ? $dadosJson['msg']
                    : [$dadosJson['msg']];

                // Monta o cabeçalho do log
                $mensagem = "VM: {$dados->nome} (ID {$vm})\n\n";

                $usuariosIgnorados = ['gabriel', 'teste'];
                $usuariosConectados = [];

                foreach ($listaUsuarios as $info) {
                    $usuario = strtolower(trim($info['usuario'] ?? ''));

                    if (in_array($usuario, $usuariosIgnorados)) {
                        continue;
                    }
                    $usuariosConectados[] = $usuario;
                    $mensagem .= "Usuário: " . ($info['usuario'] ?? '-') . "\n";
                    $mensagem .= "Sessão: " . ($info['sessao'] ?? '-') . "\n";
                    $mensagem .= "Estado: " . ($info['estado'] ?? '-') . "\n";
                    $mensagem .= "Logon: " . ($info['logon_time'] ?? '-') . "\n";
                    $mensagem .= "Tempo ocioso: " . ($info['tempo_ocioso'] ?? '-') . "\n";
                    $mensagem .= "---------------------------\n";
                }
            
                Log::info(trim($mensagem));

                if (empty($usuariosConectados)) {
                    Log::info("zero usuários conectados");
                    DB::table('vm')
                        ->where('id_vm', $vm)
                        ->update([
                            'created_at' => now(), // ou Carbon::now()
                        ]);
                }

                } catch (\Throwable $e) {
                Log::error("Erro ao processar JSON de usuários da VM {$vm}: {$e->getMessage()}");
            }
        }
    }
}