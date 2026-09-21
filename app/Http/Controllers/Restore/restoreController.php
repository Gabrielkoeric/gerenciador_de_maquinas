<?php

namespace App\Http\Controllers\Restore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Repositories\Vm\VmRepository;
use App\Repositories\Cliente\ClienteRepository;

use App\Services\Restore\ListbkpService;

class restoreController extends Controller
{
    private VmRepository $vmRepository;

public function __construct(
    VmRepository $vmRepository,
    ClienteRepository $clienteRepository,
    ListBkpService $listbkpService
) {
    $this->vmRepository = $vmRepository;
    $this->clienteRepository = $clienteRepository;
    $this->listbkpService = $listbkpService;
}

    public function index()
    {
        return view('restore.index');
    }

    public function create()
    {
        $servidores = $this->vmRepository->listarSgbd();
        $clientes = $this->clienteRepository->listarCompleto();

        return view('restore.create', compact('servidores', 'clientes'));
    }

public function store(Request $request)
{
    Log::info('Restore recebido', [
        'request' => $request->all(),
    ]);

    return back()->with('success', 'Dados recebidos com sucesso.');
}

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function backups(int $cliente)
    {
        $backups = $this->listbkpService->listarBackups($cliente);

        return response()->json($backups);
    }
}
