<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Services\EnvioEmail\EnvioEmailService;
use App\Repositories\AsyncTasks\AsyncTasksRepository;

class EnviarEmailJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $emailDestino;
    protected $assunto;
    protected $mensagem;
    protected $anexo;
    protected $usuarioId;
    protected $taskId;

    public function __construct($emailDestino, $assunto, $mensagem, $anexo = null, $usuarioId = null, $taskId = null)
    {
        $this->emailDestino = $emailDestino;
        $this->assunto = $assunto;
        $this->mensagem = $mensagem;
        $this->anexo = $anexo;
        $this->usuarioId = $usuarioId;
        $this->taskId = $taskId;
    }

    public function handle(EnvioEmailService $emailService)
    {
        try {

            if ($this->taskId) {
                DB::table('async_tasks')
                    ->where('id_async_tasks', $this->taskId)
                    ->update([
                        'horario_inicio' => now(),
                        'status' => 'Iniciado'
                    ]);
            }

            $config = null;

            if ($this->usuarioId) {
                $config = DB::table('usuario_email_config')
                    ->where('id', $this->usuarioId)
                    ->first();
            }

            $emailService->enviar(
                $this->emailDestino,
                $this->assunto,
                $this->mensagem,
                $this->anexo,
                $config
            );

            if ($this->taskId) {
                DB::table('async_tasks')
                    ->where('id_async_tasks', $this->taskId)
                    ->update([
                        'horario_fim' => now(),
                        'status' => 'Concluido',
                        'log' => 'Email enviado com sucesso'
                    ]);
            }

        } catch (\Throwable $e) {

            if ($this->taskId) {
                DB::table('async_tasks')
                    ->where('id_async_tasks', $this->taskId)
                    ->update([
                        'horario_fim' => now(),
                        'status' => 'Erro',
                        'log' => $e->getMessage()
                    ]);
            }

            throw $e; // importante pra retry
        }
    }
}