<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\ProcessRelatorioIpEmail;
use Illuminate\Support\Str;



class ApiIp2Controller extends Controller
{
    public function store(Request $request)
    {
        try {
            $arquivoIpPath = $request->file('arquivoIp')->store('arquivoIp', 'public');
            $email = $request->input('email');
            Log::info("email $email");
            $request->arquivoIp = $arquivoIpPath;
            //$nome = $request->input('nome');
            $nome = Str::random(35);
            $dados = [
                'nome' => $nome,
                'arquivoIp' => $arquivoIpPath
            ];
            $id_incidente = DB::table('incidente')->insertGetId($dados);

            $fullFilePath = storage_path('app/public/' . $arquivoIpPath);
            $handle = fopen($fullFilePath, "r");
            while (($line = fgets($handle)) !== false) {
                // Use uma expressão regular para encontrar endereços IP na linha
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
                                    'id_incidente' => $id_incidente,
                                    'quantidade' => 1
                                ];
                                DB::table('ip_incidente')->insert($dados2);
                            }
                        }else{
                            $id_ip = DB::table('ip')->where('ip', $ip)->value('id_ip');
                            $existingIpIncidente = DB::table('ip_incidente')
                                ->where('id_ip', $id_ip)
                                ->where('id_incidente', $id_incidente)
                                ->first();
                            //Log::info("IP: $ip, id_incidente: $id_incidente, $existingIpIncidente");
                            if ($existingIpIncidente) {
                                // Se já existir, atualiza a quantidade
                                DB::table('ip_incidente')
                                    ->where('id_ip', $id_ip)
                                    ->where('id_incidente', $id_incidente)
                                    ->update(['quantidade' => DB::raw('quantidade + 1')]);
                            } else {
                                // Se não existir, insere um novo registro na tabela ip_incidente
                                $dados2 = [
                                    'id_ip' => $id_ip,
                                    'id_incidente' => $id_incidente,
                                    'quantidade' => 1
                                ];
                                DB::table('ip_incidente')->insert($dados2);
                            }
                        }
                    }
                }
            }

            fclose($handle);

            Log::info("incidente no store: $id_incidente");

           /* $relatorioIp = DB::table('ip')
                ->select(
                    'ip.id_ip',
                    'ip.ip',
                    'ip.cidade',
                    'ip.regiao',
                    'ip.continente',
                    'ip.localizacao',
                    'ip.empresa',
                    'ip.postal',
                    'ip.timezone',
                    'ip_incidente.quantidade'
                )
                ->join('ip_incidente', 'ip.id_ip', '=', 'ip_incidente.id_ip')
                ->join('incidente', 'ip_incidente.id_incidente', '=', 'incidente.id_incidente')
                ->where('incidente.id_incidente', $id_incidente)
                ->get();

            $nomeArquivo = $nome . '.pdf';
            $caminhoArquivo = storage_path('app/public/arquivosIpPDF/' . $nomeArquivo);

            // Carrega a view do layout para gerar o conteúdo do PDF
            $pdf = PDF::loadView('ip.relatorioip', ['relatorioIp' => $relatorioIp]);

            // Salva o arquivo PDF no diretório específico com o nome do arquivo
            $pdf->save($caminhoArquivo);*/
            $id_async_task = DB::table('async_tasks')->insertGetId([
                'nome_async_tasks' => "Processa Relatório PDF",
                'horario_disparo' => now(), // useCurrent não é necessário aqui porque o Laravel irá preencher automaticamente o campo
                'status' => 'pendente'
            ]);
            ProcessRelatorioIpEmail::dispatch($nome, $id_incidente, $email, $id_async_task)->onQueue('padrao');
            //ProcessRelatorioIpEmail::dispatch($this->nome, $this->id_incidente, $this->email, $id_async_task )->onQueue('padrao');
            $nomeArquivo = $nome . '.pdf';
            $path = '/storage/arquivosIpPDF/' . $nomeArquivo;

// Gere a URL completa usando a função url() do Laravel
            $url = url($path);

// Retorne a resposta JSON com a URL de download
            return response()->json(['url de download' => $url], 200);
        } catch (\Exception $e) {
            Log::error('Erro no processamento do arquivo: ' . $e->getMessage());
            return response()->json(['error' => 'Erro no processamento do arquivo'], 500);
        }

    }
}
