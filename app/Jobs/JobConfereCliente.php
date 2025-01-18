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
use App\Jobs\JobConfereBackup;
use Illuminate\Support\Facades\Storage;

class JobConfereCliente implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $fileName;
    protected $email;

    public function __construct($filePath, $email)
{
    $this->filePath = $filePath;
    $this->fileName = basename($filePath);
    $this->email = $email;
}


    public function handle()
    {
        // Obtém o nome do arquivo a partir do caminho completo
        $fileName = basename($this->filePath); 

        if (preg_match('/^(\d+)_([a-zA-Z0-9_-]+)-(\d{8})-(\d{6})\.log$/', $this->fileName, $matches)) {
            $codCliente = $matches[1];  // Código do cliente
            $nomeCliente = $matches[2]; // Nome do cliente
            $data_backup = $matches[3];  // Data do backup (YYYYMMDD)
            $hora_backup = $matches[4];  // Hora do backup (HHMMSS)

            // Verifica se o cliente já existe na base com o mesmo código e nome
            $cliente = DB::table('cliente')
            ->where('cod_cliente', $codCliente)
            ->where('nome_cliente', $nomeCliente)
            ->first();

            if (!$cliente) {
                // Insere um novo cliente se não existir
                DB::table('cliente')->insert([
                    'cod_cliente' => $codCliente,
                    'nome_cliente' => $nomeCliente,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info("Novo cliente inserido: {$codCliente} - {$nomeCliente}");
            } else {
                Log::info("Cliente já existe: {$codCliente} - {$nomeCliente}");
            }
        
            // Passando o email para o JobConfereBackup
            JobConfereBackup::dispatch(storage_path('app/' . $this->filePath), $this->email)->onQueue('padrao');
            //JobConfereCliente::dispatch($filePath, $email)->onQueue('padrao');
            //JobConfereBackup::dispatch($this->filePath, $this->email)->onQueue('padrao');
            //JobConfereBackup::dispatch(storage_path('app/' . $this->filePath), $this->email)->onQueue('padrao');




        } else {
            // Apaga o arquivo inválido
            Storage::delete("public/arquivos_backup/{$fileName}");
            
            // Registra o erro no log
            Log::error("Nome do arquivo inválido: {$fileName}");
        }
    }
}