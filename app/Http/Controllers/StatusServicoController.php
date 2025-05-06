<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusServicoController extends Controller
{
    public function store(Request $request)
    {
        // Validação básica
        $dados = $request->validate([
            'servicos' => 'required|array',
            'servicos.*.nome' => 'required|string',
            'servicos.*.status' => 'required|string',
        ]);

        // Aqui você pode fazer o que quiser com os dados:
        // Exemplo: Logar para debug
        foreach ($dados['servicos'] as $servico) {
            Log::info('Status de serviço recebido', [
                'nome' => $servico['nome'],
                'status' => $servico['status'],
            ]);
        }

        // Retornar resposta para o cliente PowerShell
        return response()->json([
            'message' => 'Status dos serviços recebido com sucesso.',
            'recebidos' => count($dados['servicos']),
        ]);
    }
}
