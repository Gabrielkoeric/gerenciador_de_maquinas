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
use App\Jobs\ProcessaIpSeparado;

class SeparaIp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $nome;
    protected $id_incidente;
    protected $email;
    protected $arquivoIpPath;
    protected $id_async_task;

    public function __construct($nome, $id_incidente, $email, $arquivoIpPath, $id_async_task)
    {
        $this->nome = $nome;
        $this->id_incidente = $id_incidente;
        $this->email = $email;
        $this->arquivoIpPath = $arquivoIpPath;
        $this->id_async_task = $id_async_task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
{
    DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
        'status' => 'iniciado',
        'horario_inicio' => now()
    ]);

    $fullFilePath = storage_path('app/public/' . $this->arquivoIpPath);
    $lines = file($fullFilePath); // Lê todas as linhas do arquivo
    $totalLines = count($lines); // Total de linhas no arquivo
    $processedLines = 0; // Contador de linhas processadas

    foreach ($lines as $line) {
        $processedLines++; // Incrementa a linha processada

        // Encontra todos os IPs na linha
        preg_match_all('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $line, $matches);

        foreach ($matches[0] as $ip) {
            $ip = trim($ip);

            // Insere diretamente na tabela temp_ip
            DB::table('temp_ip')->insert([
                'ip' => $ip,
                'id_incidente' => $this->id_incidente,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Atualiza a tabela com o progresso
        DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
            'log' => "$processedLines/$totalLines"
        ]);
    }

    $id_async_task = DB::table('async_tasks')->insertGetId([
        'nome_async_tasks' => "Precessando ip's da temp",
        'horario_disparo' => now(), // useCurrent não é necessário aqui porque o Laravel irá preencher automaticamente o campo
        'status' => 'pendente'
    ]);
    //ProcessaIpSeparado::dispatch($id_incidente, $email, $id_async_task)->onQueue('padrao');
    Log::info("erro ta aq");
    //Log::info("id_incidente $id_incidente");
    Log::info("email $email");
    Log::info("id_async_task $id_async_task");
    ProcessaIpSeparado::dispatch($this->id_incidente, $this->email, $id_async_task)->onQueue('padrao');

    // Finaliza a tarefa
    DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
        'status' => 'concluido',
        'horario_fim' => now()
    ]);

    Log::info("IP parsing concluído para o incidente: $this->id_incidente");
}
}