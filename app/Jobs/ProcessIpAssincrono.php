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

class ProcessIpAssincrono implements ShouldQueue
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
        $handle = fopen($fullFilePath, "r");
        while (($line = fgets($handle)) !== false) {
            // Use a regular expression to find IP addresses in the line
            preg_match_all('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $line, $matches);
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
                        //Log::info("IP: $ip, id_incidente: $this->id_incidente, $existingIpIncidente");
                        if ($existingIpIncidente) {
                            // If it already exists, update the quantity
                            DB::table('ip_incidente')
                                ->where('id_ip', $id_ip)
                                ->where('id_incidente', $this->id_incidente)
                                ->update(['quantidade' => DB::raw('quantidade + 1')]);
                        } else {
                            // If it doesn't exist, insert a new record into the ip_incidente table
                            $dados2 = [
                                'id_ip' => $id_ip,
                                'id_incidente' => $this->id_incidente,
                                'quantidade' => 1
                            ];
                            DB::table('ip_incidente')->insert($dados2);
                        }
                    }
                }
            }
        }

        fclose($handle);

        Log::info("incidente no store: $this->id_incidente");

        DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
            'status' => 'concluido',
            'horario_fim' => now()
        ]);

        $id_async_task = DB::table('async_tasks')->insertGetId([
            'nome_async_tasks' => "Gera PDF",
            'horario_disparo' => now(), // useCurrent não é necessário aqui porque o Laravel irá preencher automaticamente o campo
            'status' => 'pendente'
        ]);
        ProcessRelatorioIpEmail::dispatch($this->nome, $this->id_incidente, $this->email, $id_async_task )->onQueue('padrao');
    }

}
