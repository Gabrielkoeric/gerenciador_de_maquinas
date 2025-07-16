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

        $repo = DB::table('repositorios')->where('id_repositorios', $execucao->id_repositorio)->first();

        $remotePath = "\"{$repo->rclone}:{$repo->origem}\"";
        $destino = $repo->destino;
        $logFile = $repo->log_dir;
        $tags = $repo->tags ?? '';
        $tipoCopia = $repo->tipo_copia;

        $cmd = "/usr/bin/rclone {$tipoCopia} {$remotePath} {$destino} --log-file=\"{$logFile}\" {$tags}";
        Log::info("Comando Rclone montado para execução {$this->id_execucao}: {$cmd}");
        
        // Comando para executar via SSH remoto
        //$sshCommand = "sshpass -p 'teste' ssh -o StrictHostKeyChecking=no teste@192.168.x.x \"$cmd\"";

        $senha = '';
        $senhaSegura = escapeshellarg($senha); 
        $sshCommand = "sshpass -p $senhaSegura ssh -o StrictHostKeyChecking=no root@192.168.x.x \"$cmd\"";


        $process = Process::fromShellCommandline($sshCommand);
        $process->setTimeout(null);
        $process->run();

            if ($process->isSuccessful()) {
                DB::table('rclone_execucoes')->where('id_execucao', $this->id_execucao)->update([
                    'status' => 'concluido',
                    'fim' => now(),
                    'log_path' => $logFile,
                ]);
            } else {
                DB::table('rclone_execucoes')->where('id_execucao', $this->id_execucao)->update([
                    'status' => 'erro',
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
        WHERE status = 'pendente'
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
