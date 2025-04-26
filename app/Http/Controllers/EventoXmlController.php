<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventoXmlController extends Controller
{
    public function receber(Request $request)
{
    try {
        // Captura o XML bruto enviado no corpo da requisição
        $xmlContent = $request->getContent();

        // Loga o XML completo no log padrão
        Log::info("XML completo recebido:\n" . $xmlContent);

        // Converte XML para um objeto SimpleXMLElement
        $xml = simplexml_load_string($xmlContent, "SimpleXMLElement", LIBXML_NOCDATA);

        if ($xml === false) {
            return response()->json(['error' => 'XML inválido'], 400);
        }
/*
        // Também loga dados específicos para facilitar consulta
        Log::info("Evento XML recebido (resumo):", [
            'TimeCreated' => (string) $xml->System->TimeCreated['SystemTime'] ?? 'N/A',
            'Provider'    => (string) $xml->System->Provider['Name'] ?? 'N/A',
            'EventID'     => (string) $xml->System->EventID ?? 'N/A',
        ]);
*/
        return response()->json(['status' => 'ok'], 200);
    } catch (\Exception $e) {
        Log::error("Erro ao processar XML: " . $e->getMessage());
        return response()->json(['error' => 'Erro interno'], 500);
    }
}}
