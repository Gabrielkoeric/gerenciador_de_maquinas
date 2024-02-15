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
    /*use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sigla;

    public function __construct($sigla)
    {
        $this->sigla = $sigla;
    }*/

    public function handle()
    {
        Log::info('Job executado');}/*
        //Log::info('Job DownloadBandeiraJob iniciado para ');
        $asyncTask = DB::table('async_tasks')->insert([
            'horario_disparo' => now(),
            'status' => 'executando',
        ]);

        try {
            $diretorio = storage_path('app/public/bandeiraImagem/');
            $nomeArquivo = $this->sigla . '.png';
            $caminhoCompleto = $diretorio . $nomeArquivo;

            // Verificar se a imagem já existe no diretório
            if (!file_exists($caminhoCompleto)) {
                // Baixar a imagem da bandeira
                $response = Http::get("https://flagcdn.com/w320/{$this->sigla}.png");

                if ($response->successful()) {
                    // Salvar a imagem no diretório
                    Storage::put("bandeiraImagem/{$nomeArquivo}", $response->body());
                }
            }
        } catch (\Exception $e) {
            DB::table('async_tasks')
                ->where('condicao', '=', $valorDaCondicao)
                ->update([
                    'horario_fim' => now(),
                    'status' => 'erro',
                    'log' => 'Erro durante o download da bandeira: ' . $e->getMessage(),
                ]);


            return; // Importante adicionar um return para evitar que o código abaixo seja executado em caso de erro.
        }

        $asyncTask->update([
            'horario_fim' => now(),
            'status' => 'concluido',
            'log' => 'Download da bandeira realizado com sucesso.',
        ]);
    }*/

}
