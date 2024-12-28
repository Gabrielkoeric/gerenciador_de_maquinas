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

class ProcessIpAssincrono2 implements ShouldQueue
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
        $lines = file($fullFilePath); // Ler todas as linhas do arquivo

        $newLines = []; // Array para armazenar linhas que não foram processadas

        foreach ($lines as $line) {
            preg_match_all('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $line, $matches);
            $lineProcessed = false;

            foreach ($matches[0] as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    $existingIp = DB::table('ip')->where('ip', $ip)->first();
                    if (!$existingIp) {
                        $url = "https://ipinfo.io/$ip/json?token=5e2c5aa71f13aa";
                        $response = file_get_contents($url);
                        $jsonData = json_decode($response);
                        if (!empty($jsonData->city)) {
                            $newData = [
                                'ip' => $ip,
                                'cidade' => $jsonData->city ?? null,
                                'regiao' => $jsonData->region ?? null,
                                'continente' => $jsonData->country ?? null,
                                'localizacao' => $jsonData->loc ?? null,
                                'empresa' => $jsonData->org ?? null,
                                'postal' => $jsonData->postal ?? null,
                                'timezone' => $jsonData->timezone ?? null,
                            ];
                            $id_ip = DB::table('ip')->insertGetId($newData);
                            $dados2 = [
                                'id_ip' => $id_ip,
                                'id_incidente' => $this->id_incidente,
                                'quantidade' => 1
                            ];
                            DB::table('ip_incidente')->insert($dados2);
                        }
                    } else {
                        $id_ip = DB::table('ip')->where('ip', $ip)->value('id_ip');
                        $existingIpIncidente = DB::table('ip_incidente')
                            ->where('id_ip', $id_ip)
                            ->where('id_incidente', $this->id_incidente)
                            ->first();
                        if ($existingIpIncidente) {
                            DB::table('ip_incidente')
                                ->where('id_ip', $id_ip)
                                ->where('id_incidente', $this->id_incidente)
                                ->update(['quantidade' => DB::raw('quantidade + 1')]);
                        } else {
                            $dados2 = [
                                'id_ip' => $id_ip,
                                'id_incidente' => $this->id_incidente,
                                'quantidade' => 1
                            ];
                            DB::table('ip_incidente')->insert($dados2);
                        }
                    }
                    $lineProcessed = true; // Marca que a linha foi processada
                }
            }

            if (!$lineProcessed) {
                $newLines[] = $line; // Adiciona linha não processada ao array
            }
        }

        // Escreve as linhas restantes de volta no arquivo
        file_put_contents($fullFilePath, implode('', $newLines));

        Log::info("incidente no store: $this->id_incidente");

        DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
            'status' => 'concluido',
            'horario_fim' => now()
        ]);

        $id_async_task = DB::table('async_tasks')->insertGetId([
            'nome_async_tasks' => "Gera PDF",
            'horario_disparo' => now(),
            'status' => 'pendente'
        ]);
        ProcessRelatorioIpEmail2::dispatch($this->nome, $this->id_incidente, $this->email, $id_async_task)->onQueue('padrao');
        DownloadBandeiraJob::dispatch()->onQueue('padrao');
    }
}