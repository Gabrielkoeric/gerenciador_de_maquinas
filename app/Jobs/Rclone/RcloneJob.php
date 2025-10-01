<?php

namespace App\Jobs\Rclone;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class RcloneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id_execucao;

    public function __construct($id_execucao)
    {
        $this->id_execucao = $id_execucao;
    }

    public function handle()
    {
        // Buscar a execução
        $execucao = DB::table('rclone_execucoes')->where('id_execucao', $this->id_execucao)->first();

        DB::table('rclone_execucoes')->where('id_execucao', $this->id_execucao)->update([
            'status' => 'executando',
            'inicio' => now(),
        ]);

        $repo = DB::table('repositorios as r')
            ->join('vm as v', 'r.id_server_bkp', '=', 'v.id_vm')
            ->join('ip_lan as ip', 'v.id_ip_lan', '=', 'ip.id_ip_lan')
            ->select(
                'r.*',        // todas as colunas da tabela repositorios
                'ip.ip'       // IP da tabela ip_lan
            )
        ->where('r.id_repositorios', $execucao->id_repositorio)
        ->first();

        $credenciais = DB::table('usuario_vm')->where('id_vm', $repo->id_server_bkp)->first();

        $remotePath = "\"{$repo->rclone}:{$repo->origem}\"";
        $destino = $repo->destino;
        $logFile = $repo->log_dir;
        $tags = $repo->tags ?? '';
        $tipoCopia = $repo->tipo_copia;

        $cmd = "/usr/bin/rclone {$tipoCopia} {$remotePath} {$destino} --log-file=\"{$logFile}\" {$tags}";
        Log::info("Comando Rclone montado para execução {$this->id_execucao}: {$cmd}");
        
        // Comando para executar via SSH remoto
        //$sshCommand = "sshpass -p 'teste' ssh -o StrictHostKeyChecking=no teste@192.168.x.x \"$cmd\"";

        $senha = $credenciais->senha;
        $senhaSegura = escapeshellarg($senha); 
        //$sshCommand = "sshpass -p $senhaSegura ssh -o StrictHostKeyChecking=no $credenciais->usuario@$repo->ip_lan \"$cmd\"";
        $sshCommand = "sshpass -p $senhaSegura ssh -o StrictHostKeyChecking=no {$credenciais->usuario}@{$repo->ip} \"$cmd\"";
        //Log::info("senha segura: $senhaSegura");
        //Log::info("senha: $senha");
        //Log::info("credenciais usuario: $credenciais->usuario");
        //Log::info("repo ip: $repo->ip");
        //Log::info("sshCommand: $sshCommand");




        $process = Process::fromShellCommandline($sshCommand);
        $process->setTimeout(null);
        $process->run();

            if ($process->isSuccessful()) {
                DB::table('rclone_execucoes')->where('id_execucao', $this->id_execucao)->update([
                    'status' => 'concluido',
                    'comando_rclone' => $cmd,
                    'fim' => now(),
                    'log_path' => $logFile,
                ]);
            } else {
                DB::table('rclone_execucoes')->where('id_execucao', $this->id_execucao)->update([
                    'status' => 'erro',
                    'comando_rclone' => $cmd,
                    'fim' => now(),
                    'erro' => $process->getErrorOutput(),
                    'log_path' => $logFile,
                ]);
            }
        /*$proxima = DB::table('rclone_execucoes')
            ->where('status', 'pendente')
            ->orderBy('id_execucao')
            ->first();
*/
/*$proxima = DB::transaction(function () {
    $execucao = DB::table('rclone_execucoes')
        ->where('status', 'pendente')
        ->orderBy('id_execucao')
        ->limit(1)
        ->lockForUpdate(skipLocked: true)
        ->first();

    if ($execucao) {
        DB::table('rclone_execucoes')
            ->where('id_execucao', $execucao->id_execucao)
            ->update([
                'status' => 'em fila',
                'inicio' => now(),
            ]);
    }

    return $execucao;
});*/
$proxima = DB::transaction(function () {
    $execucao = DB::selectOne("
        SELECT * FROM rclone_execucoes
        WHERE status = 'pendente' and tipo = 'manual'
        ORDER BY id_execucao
        LIMIT 1
        FOR UPDATE SKIP LOCKED
    ");

    if ($execucao) {
        DB::table('rclone_execucoes')
            ->where('id_execucao', $execucao->id_execucao)
            ->update([
                'status' => 'em fila',
                'inicio' => now(),
            ]);
    }

    return $execucao;
});

        if ($proxima) {
            self::dispatch($proxima->id_execucao)->onQueue('rclone');
        }
            
    }
}
