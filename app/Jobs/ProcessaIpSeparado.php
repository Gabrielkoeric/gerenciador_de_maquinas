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

class ProcessaIpSeparado implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $id_incidente;
    protected $email;
    protected $id_async_task;
    protected $nome;

    public function __construct($nome, $id_incidente, $email, $id_async_task)
    {
        $this->nome = $nome;
        $this->id_incidente = $id_incidente;
        $this->email = $email;
        $this->id_async_task = $id_async_task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
{
    Log::info("job processa ip"); 

    // Exemplo de como acessar id_async_task corretamente
    Log::info("ID da tarefa assíncrona: " . $this->id_async_task);
    
        // Agrupa os IPs na tabela temp_ip
        $groupedIps = DB::table('temp_ip')
            ->select('ip', 'id_incidente', DB::raw('COUNT(*) as quantidade'))
            ->groupBy('ip', 'id_incidente')
            ->get();

        foreach ($groupedIps as $tempIp) {
            $ip = trim($tempIp->ip);
            $idIncidente = $tempIp->id_incidente;
            $quantidade = $tempIp->quantidade;

            // Valida se o IP já existe na tabela 'ip'
            $existingIp = DB::table('ip')->where('ip', $ip)->first();

            if (!$existingIp) {
                // Caso o IP não exista, consulta a API ipinfo.io
                $url = "https://ipinfo.io/$ip/json?token=5e2c5aa71f13aa";
                $response = @file_get_contents($url); // Usa @ para evitar warnings caso a API não responda
                $jsonData = $response ? json_decode($response) : null;

                if (!empty($jsonData->city)) {
                    // Insere o novo IP na tabela 'ip'
                    $idIp = DB::table('ip')->insertGetId([
                        'ip' => $ip,
                        'cidade' => $jsonData->city ?? null,
                        'regiao' => $jsonData->region ?? null,
                        'continente' => $jsonData->country ?? null,
                        'localizacao' => $jsonData->loc ?? null,
                        'empresa' => $jsonData->org ?? null,
                        'postal' => $jsonData->postal ?? null,
                        'timezone' => $jsonData->timezone ?? null,
                    ]);
                } else {
                    // Se a API não retornar informações, pula para o próximo IP
                    Log::warning("Não foi possível obter dados para o IP: $ip");
                    continue;
                }
            } else {
                $idIp = $existingIp->id_ip;
            }

            // Valida se o IP já está relacionado ao incidente
            $existingIpIncidente = DB::table('ip_incidente')
                ->where('id_ip', $idIp)
                ->where('id_incidente', $idIncidente)
                ->first();

            if ($existingIpIncidente) {
                // Atualiza a quantidade na tabela ip_incidente
                DB::table('ip_incidente')
                    ->where('id_ip', $idIp)
                    ->where('id_incidente', $idIncidente)
                    ->update(['quantidade' => DB::raw('quantidade + ' . $quantidade)]);
            } else {
                // Insere um novo registro na tabela ip_incidente
                DB::table('ip_incidente')->insert([
                    'id_ip' => $idIp,
                    'id_incidente' => $idIncidente,
                    'quantidade' => $quantidade,
                ]);
            }
        }
        Log::info("disparando tarefas");
        //$id_incidente = 1;
        ProcessRelatorioIpEmail2::dispatch($this->nome, $this->id_incidente, $this->email, $this->id_async_task)->onQueue('padrao');
        DownloadBandeiraJob::dispatch()->onQueue('padrao');
        Log::info("Processamento da tabela temp_ip concluído.");
    }
}