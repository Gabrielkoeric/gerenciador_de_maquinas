<?php

namespace App\Http\Controllers\SecaoCloud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\Cliente\ClienteRepository;

use App\Services\SecaoCloudLoteService\SecaoCloudLoteService;

class SecaoCloudLoteController extends Controller
{
    protected ClienteRepository $cliente;
    protected SecaoCloudLoteService $service;

    public function __construct(
        ClienteRepository $cliente,
        SecaoCloudLoteService $service
    ) {
        $this->cliente = $cliente;
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filtroClientes = $request->input('clientes', []);

        $query = $this->cliente->getClientesLicencasLote();

        if (!empty($filtroClientes)) {
            $query->whereIn('cliente_escala.id_cliente_escala', $filtroClientes);
        }

        

        $dados = $query->get();

        $todosClientes = $this->cliente->listarCompleto();
    
        return view('secao_cloud_lote.index', [
            'dados' => $dados,
            'todosClientes' => $todosClientes,
            'filtroClientes' => $filtroClientes
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->service->processar(
            $id,
            (int) $request->coletor,
            (int) $request->desktop
        );

        $this->cliente->updateLicencas(
            $id,
            $request->coletor,
            $request->desktop,
            $request->licenca
        );

        return response()->json(['success' => true]);
    }
}