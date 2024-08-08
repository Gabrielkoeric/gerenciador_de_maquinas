<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DownloadBandeiraJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id_async_task;

    public function __construct()
    {
        // Não precisamos de parâmetros no construtor
    }

    public function handle()
    {
        Log::info('Job DownloadBandeiraJob iniciado');
/*
        // Criar a entrada na tabela async_tasks
        $this->id_async_task = DB::table('async_tasks')->insertGetId([
            'nome_async_tasks' => "Download Bandeira",
            'horario_disparo' => now(),
            'status' => 'executando',
        ]);*/

        try {
            // Obter valores únicos da coluna continente
            $continentes = DB::table('ip')
                ->select('continente')
                ->distinct()
                ->pluck('continente');

            $diretorio = storage_path('app/public/bandeiraImagem/');

            foreach ($continentes as $continente) {
                $sigla = strtolower($continente); // Assumindo que a sigla é o nome do continente em minúsculas
                $nomeArquivo = $sigla . '.png';
                $caminhoCompleto = $diretorio . $nomeArquivo;

                // Verificar se a imagem já existe no diretório
                if (!file_exists($caminhoCompleto)) {
                    // Baixar a imagem da bandeira
                    $response = Http::get("https://flagcdn.com/w320/{$sigla}.png");

                    if ($response->successful()) {
                        // Salvar a imagem no diretório
                        Storage::put("public/bandeiraImagem/{$nomeArquivo}", $response->body());
                        Log::info("Download da bandeira para {$sigla} realizado com sucesso.");
                    } else {
                        Log::warning("Falha ao baixar a imagem da bandeira para {$sigla}.");
                    }
                }
            }
/*
            // Atualizar a entrada na tabela async_tasks
            DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
                'horario_fim' => now(),
                'status' => 'concluido',
                'log' => 'Download das bandeiras realizado com sucesso.',
            ]);
            */
        } catch (\Exception $e) {
          /*  // Atualizar a entrada na tabela async_tasks em caso de erro
            DB::table('async_tasks')->where('id_async_tasks', $this->id_async_task)->update([
                'horario_fim' => now(),
                'status' => 'erro',
                'log' => 'Erro durante o download das bandeiras: ' . $e->getMessage(),
            ]);*/

            Log::error('Erro durante o download das bandeiras: ' . $e->getMessage());

            return; // Importante adicionar um return para evitar que o código abaixo seja executado em caso de erro.
        }
    }
}
