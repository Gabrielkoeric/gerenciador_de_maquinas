<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiServicoController extends Controller
{
    public function receber(Request $request)
    {
        try {
            // Captura o conteúdo JSON como array
            $dados = $request->json()->all();

            // Loga o JSON completo no log padrão
            Log::info("Status dos serviços recebido:", $dados);

            return response()->json(['status' => 'ok'], 200);
        } catch (\Exception $e) {
            Log::error("Erro ao receber status dos serviços: " . $e->getMessage());
            return response()->json(['error' => 'Erro interno'], 500);
        }
    }
}
