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
        $handle = fopen($fullFilePath, "r"); // Abre o arquivo para leitura linha por linha
        $batchData = []; // Array para armazenar os dados do lote
        $totalLines = 0;
        $processedLines = 0;

        while (($line = fgets($handle)) !== false) {
            $totalLines++; // Contador total de linhas no arquivo
            // Encontra todos os IPs na linha
            preg_match_all('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $line, $matches);

            foreach ($matches[0] as $ip) {
                $batchData[] = [
                    'ip' => trim($ip),
                    'id_incidente' => $this->id_incidente,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insere em lote de 1000 registros
            if (count($batchData) >= 1000) {
                DB::table('temp_ip')->insert($batchData);
                $batchData = []; // Limpa o lote após a inserção
            }

            $processedLines++; // Incrementa as linhas processadas

            // Atualiza a tabela com o progresso a cada 1000 linhas ou quando o arquivo for concluído
            if ($processedLines % 1000 === 0 || $processedLines === $totalLines) {
                DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
                    'log' => "$processedLines/$totalLines"
                ]);
            }
        }

        // Insere o restante das linhas restantes após a última iteração
        if (!empty($batchData)) {
            DB::table('temp_ip')->insert($batchData);
        }

        fclose($handle); // Fecha o arquivo após o processamento

        // Cria um novo async task para o próximo processo
        $id_async_task = DB::table('async_tasks')->insertGetId([
            'nome_async_tasks' => "Processando IPs da temp",
            'horario_disparo' => now(),
            'status' => 'pendente'
        ]);

        Log::info("Processamento de IPs iniciado para o incidente: $this->id_incidente");

        // Envia o job para a fila
        ProcessaIpSeparado::dispatch($this->nome, $this->id_incidente, $this->email, $id_async_task)->onQueue('padrao');

        // Finaliza a tarefa
        DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
            'status' => 'concluido',
            'horario_fim' => now()
        ]);

        Log::info("IP parsing concluído para o incidente: $this->id_incidente");
    }
}
