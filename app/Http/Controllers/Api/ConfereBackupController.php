<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Jobs\JobConfereCliente;

class ConfereBackupController extends Controller
{
    public function store(Request $request)
{
    // Validação do arquivo e do campo email
    $validator = Validator::make($request->all(), [
        'arquivo' => 'required|file|mimes:log,txt|max:2048', // Apenas arquivos de log ou txt com até 2MB
        'email' => 'required|email', // Campo de e-mail obrigatório e válido
    ]);

    /*if ($validator->fails()) {
        return response()->json([
            'message' => 'Arquivo invalido',
            'errors' => $validator->errors()
        ], 400);
    }*/

    // Fazendo o upload do arquivo para o diretório 'arquivos_backup' dentro de 'storage/app/public'
    $file = $request->file('arquivo');
    
    // Obtendo o nome original do arquivo
    $fileName = $file->getClientOriginalName();  // Usa o nome original do arquivo

    // Armazena o arquivo com o nome original
    $filePath = $file->storeAs('public/arquivos_backup', $fileName); 
    // Altere a linha de armazenamento do arquivo
    //$filePath = $file->storeAs('public/arquivos_backup', $fileName); 

     // Disparar o job para processar o arquivo, passando o caminho e o email
     //JobConfereCliente::dispatch(storage_path('app/' . $filePath), $request->email)->onQueue('padrao');
     JobConfereCliente::dispatch($filePath, $request->email)->onQueue('padrao');
     //JobConfereCliente::dispatch(storage_path('app/' . $filePath), $request->email)->onQueue('padrao');



    // Se você quiser salvar o caminho do arquivo em banco, pode fazer aqui (por exemplo, tabela conferencia_backup)

    // Retorno da resposta com sucesso
    return response()->json([
        'message' => 'Arquivo de backup carregado com sucesso!',
        'file_path' => Storage::url($filePath) // Retorna a URL pública do arquivo
    ], 200);
}
}
