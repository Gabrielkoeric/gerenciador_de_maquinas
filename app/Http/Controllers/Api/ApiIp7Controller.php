<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\SeparaIp;
use App\Jobs\ProcessaIpSeparado;
use App\Jobs\ProcessRelatorioIpEmail2;
use App\Jobs\DownloadBandeiraJob;
use Illuminate\Support\Str;



class ApiIp7Controller extends Controller
{
    public function store(Request $request)
    {
        try {
            $arquivoIpPath = $request->file('arquivoIp')->store('arquivoIp', 'public');
            $email = $request->input('email');
            Log::info("arquivoIpPath $arquivoIpPath");
            $arquivoIpPath = "arquivoIp/full.txt";
            Log::info("arquivoIpPath $arquivoIpPath");
            Log::info("email $email");
            $request->arquivoIp = $arquivoIpPath;
            //$nome = $request->input('nome');
            $nome = Str::random(35);
            $dados = [
                'nome' => $nome,
                'arquivoIp' => $arquivoIpPath
            ];
            $id_incidente = DB::table('incidente')->insertGetId($dados);
            $id_async_task = DB::table('async_tasks')->insertGetId([
                'nome_async_tasks' => "Salvando os IP's",
                'horario_disparo' => now(), // useCurrent não é necessário aqui porque o Laravel irá preencher automaticamente o campo
                'status' => 'pendente'
            ]);
            Log::info("disparando o processo assincrono $id_async_task");
            // Passando os valores diretamente para os jobs
        SeparaIp::dispatch($nome, $id_incidente, $email, $arquivoIpPath, $id_async_task)->onQueue('padrao');
        ProcessaIpSeparado::dispatch($nome, $id_incidente, $email, $id_async_task)->onQueue('padrao');
        ProcessRelatorioIpEmail2::dispatch($nome, $id_incidente, $email, $id_async_task)->onQueue('padrao');
        DownloadBandeiraJob::dispatch()->onQueue('padrao');
            //ProcessIpAssincrono2::dispatch($nome, $id_incidente, $email, $arquivoIpPath, $id_async_task)->onQueue('padrao');
            //ProcessIpAssincrono::dispatch($nome, $id_incidente, $email, $arquivoIpPath, $id_async_task)->timeout(300)->onQueue('padrao');
            //ProcessIpAssincrono::dispatch($nome, $id_incidente, $email, $arquivoIpPath, $id_async_task)->onQueue('padrao')->delay(now()->addSeconds(300)); // Definindo o tempo limite para 5 minutos (300 segundos)


            Log::info("disparado o processo assincrono");
            $nomeArquivo = $nome . '.pdf';
            $path = '/storage/arquivosIpPDF/' . $nomeArquivo;

            // Gere a URL completa usando a função url() do Laravel
            $url = url($path);

            // Retorne a resposta JSON com a URL de download
            return response()->json(['Assim que finalizado o processamento do arquivo e gerado o relatório o mesmo estara disponivel para download na url' => $url], 200);
        } catch (\Exception $e) {
            Log::error('Erro no processamento do arquivo: ' . $e->getMessage());
            return response()->json(['error' => 'Erro no processamento do arquivo'], 500);
        }
    }
}
