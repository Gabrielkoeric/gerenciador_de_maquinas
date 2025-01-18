<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JobConfereBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $fileName;
    protected $idCliente;

    public function __construct($filePath, $idCliente)
    {
        $this->filePath = $filePath;
        $this->fileName = basename($filePath);
        $this->idCliente = $idCliente;
    }

    public function handle()
    {
        Log::info("Processando arquivo: {$this->fileName}");
        
        /*if (!Storage::exists($this->filePath)) {
            Log::error("Arquivo não encontrado: {$this->filePath}");
            return;
        }*/

        Log::info("Caminho real do arquivo: " . storage_path('app/public/' . $this->filePath));
if (!Storage::disk('public')->exists($this->filePath)) {
    Log::error("Arquivo não encontrado: {$this->filePath}");
    return;
}


        $content = Storage::get($this->filePath);
        preg_match_all('/pg_dump: dumping contents of table \"(.*?)\.(.*?)\"/', $content, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $modulo = $match[1];
            $nomeTabela = $match[2];

            // Verifica se já existe na tabela cliente_tabelas
            $relacaoExiste = DB::table('cliente_tabelas')
                ->join('tabelas', 'cliente_tabelas.id_tabelas', '=', 'tabelas.id_tabelas')
                ->where('cliente_tabelas.id_cliente', $this->idCliente)
                ->where('tabelas.modulo', $modulo)
                ->where('tabelas.nome_tabelas', $nomeTabela)
                ->exists();

            if (!$relacaoExiste) {
                // Verifica se a tabela já existe na tabela 'tabelas'
                $tabela = DB::table('tabelas')
                    ->where('modulo', $modulo)
                    ->where('nome_tabelas', $nomeTabela)
                    ->first();

                if (!$tabela) {
                    $tabelaId = DB::table('tabelas')->insertGetId([
                        'modulo' => $modulo,
                        'nome_tabelas' => $nomeTabela,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    Log::info("Nova tabela inserida: {$modulo}.{$nomeTabela}");
                } else {
                    $tabelaId = $tabela->id_tabelas;
                }

                // Insere relacionamento
                DB::table('cliente_tabelas')->insert([
                    'id_cliente' => $this->idCliente,
                    'id_tabelas' => $tabelaId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                Log::info("Relacionamento criado: Cliente {$this->idCliente} -> {$modulo}.{$nomeTabela}");
            }
        }
    }
}
