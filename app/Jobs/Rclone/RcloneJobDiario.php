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
use Carbon\Carbon;
use App\Jobs\Rclone\RcloneJobDiario;

class RcloneJobDiario implements ShouldQueue
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

        $data = Carbon::now()->subHour();
        $dataPath = $data->format('Y/m/d');

        // Monta origem e destino com a data
        $remotePath = "\"{$repo->rclone}{$repo->origem}{$dataPath}/\"";
        $destino = "{$repo->destino}{$dataPath}/";

        $logFile = $repo->log_dir;
        $tags = $repo->tags ?? '';
        $tipoCopia = $repo->tipo_copia;

        $cmd = "rclone {$tipoCopia} {$remotePath} {$destino} --log-file=\"{$logFile}\" {$tags}";
        //Log::info("Comando Rclone montado para execução {$this->id_execucao}: {$cmd}");

        $senha = $credenciais->senha;
        $senhaSegura = escapeshellarg($senha); 
        $sshCommand = "sshpass -p $senhaSegura ssh -o StrictHostKeyChecking=no {$credenciais->usuario}@{$repo->ip} \"$cmd\"";

        $process = Process::fromShellCommandline($sshCommand);
        $process->setTimeout(null);
        $process->run();
            
if ($process->isSuccessful()) {
    $sshCommandLog = "sshpass -p $senhaSegura ssh -o StrictHostKeyChecking=no {$credenciais->usuario}@{$repo->ip} 'tail -n 1 {$logFile}'";

    $processLog = Process::fromShellCommandline($sshCommandLog);
    $processLog->setTimeout(30);
    $processLog->run();

    $ultimaLinha = $processLog->isSuccessful() ? trim($processLog->getOutput()) : '';

    
    //Log::info("sucesso");
    //Log::info("ultima linha do log: $ultimaLinha");
    // Inicializa os valores
    $qtdTransferidos = null;
    $qtdCheck = null;
    $bytesTransferidos = null;
    $resumo = $ultimaLinha;

    // Tenta decodificar como JSON
    $json = json_decode($ultimaLinha, true);
if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
    $stats = $json['stats'] ?? [];
    $qtdTransferidos   = $stats['transfers'] ?? null;
    $qtdCheck          = $stats['checks'] ?? null;
    $bytesTransferidos = $stats['bytes'] ?? null;
    $resumo            = json_encode($json, JSON_UNESCAPED_UNICODE);
}

    DB::table('rclone_execucoes')->where('id_execucao', $this->id_execucao)->update([
        'status'                  => 'concluido',
        'comando_rclone'          => $cmd,
        'fim'                     => now(),
        'log_path'                => $logFile,
        'erro'                    => $resumo, // mesmo em sucesso guarda o resumo
        'qtd_arquivos_transferidos' => $qtdTransferidos,
        'qtd_arquivos_check'        => $qtdCheck,
        'bytes_transferidos'        => $bytesTransferidos,
    ]);
} else {
    //Log::info("erro");
    DB::table('rclone_execucoes')->where('id_execucao', $this->id_execucao)->update([
        'status' => 'erro',
        'comando_rclone' => $cmd,
        'fim' => now(),
        'erro' => $process->getErrorOutput(),
        'log_path' => $logFile,
    ]);
}
/*
        $execucoesPendentes = DB::table('rclone_execucoes')
        ->where('status', 'pendente')
        ->where('tipo', 'diario')
        ->orderBy('id_execucao')
        ->limit(1)
        ->get();

    foreach ($execucoesPendentes as $execucao) {
    RcloneJobDiario::dispatch($execucao->id_execucao)->onQueue('diario');
    }
    */
    }
}
