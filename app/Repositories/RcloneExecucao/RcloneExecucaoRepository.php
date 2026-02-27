<?php

namespace App\Repositories\RcloneExecucao;


use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RcloneExecucaoRepository
{
    public function criarExecucaoDiaria(int $idRepositorio): int
    {
        return DB::table('rclone_execucoes')->insertGetId([
            'id_repositorio' => $idRepositorio,
            'tipo' => 'diario',
            'status' => 'pendente',
            'disparo' => Carbon::now(),
            'inicio' => null,
            'fim' => null,
            'qtd_arquivos_transferidos' => null,
            'qtd_arquivos_check' => null,
            'bytes_transferidos' => null,
            'log_path' => null,
            'erro' => null,
        ]);
    }

    public function findById(int $id)
    {
        return DB::table('rclone_execucoes')
            ->where('id_execucao', $id)
            ->first();
    }

    public function marcarExecutando(int $id): void
    {
        DB::table('rclone_execucoes')
            ->where('id_execucao', $id)
            ->update([
                'status' => 'executando',
                'inicio' => now(),
            ]);
    }

    public function finalizarSucesso(
        int $id,
        string $cmd,
        $fim,
        string $logFile,
        string $resumo,
        int $qtdTransferidos,
        int $qtdCheck,
        int $bytesTransferidos
    ): void {
        DB::table('rclone_execucoes')
            ->where('id_execucao', $id)
            ->update([
                'status' => 'concluido',
                'comando_rclone' => $cmd,
                'fim' => $fim,
                'log_path' => $logFile,
                'erro' => $resumo,
                'qtd_arquivos_transferidos' => $qtdTransferidos,
                'qtd_arquivos_check' => $qtdCheck,
                'bytes_transferidos' => $bytesTransferidos,
            ]);
    }

    public function finalizarErro(
        int $id,
        string $cmd,
        $fim,
        string $logFile,
        string $erro
    ): void {
        DB::table('rclone_execucoes')
            ->where('id_execucao', $id)
            ->update([
                'status' => 'erro',
                'comando_rclone' => $cmd,
                'fim' => $fim,
                'log_path' => $logFile,
                'erro' => $erro,
            ]);
    }
}