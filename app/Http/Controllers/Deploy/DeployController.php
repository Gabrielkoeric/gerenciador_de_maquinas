<?php

namespace App\Http\Controllers\Deploy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\VmServico\VmServicoRepository;
use App\Repositories\Vm\VmRepository;
use App\Repositories\Cliente\ClienteRepository;
use App\Repositories\AsyncTasks\AsyncTasksRepository;
use App\Repositories\Servicos\ServicosRepository;

use App\Jobs\Deploy\Server;

class DeployController extends Controller
{
    protected VmServicoRepository $vmServicoRepository;
    protected VmRepository $vmRepository;
    protected ClienteRepository $clienteRepository;
    protected AsyncTasksRepository $asyncTasksRepository;
    protected ServicosRepository $servicosRepository;

    public function __construct(
        VmServicoRepository $vmServicoRepository,
        VmRepository $vmRepository,
        ClienteRepository $clienteRepository,
        AsyncTasksRepository $asyncTasksRepository,
        ServicosRepository $servicosRepository
        )
    {
        $this->vmServicoRepository = $vmServicoRepository;
        $this->vmRepository = $vmRepository;
        $this->clienteRepository = $clienteRepository;
        $this->asyncTasksRepository = $asyncTasksRepository;
        $this->servicosRepository = $servicosRepository;
    }

    public function index()
    {
        return view('deploy.index');
    }

    public function show($id)
    {
        switch ($id) {
            case 'EscalaServer':
                $ultimaPorta = $this->vmServicoRepository->lastPort('EscalaServer');
                $vms = $this->vmRepository->getByTipo('escalaserver');
                $clientes = $this->clienteRepository->getClientesComApelido();
                return view('deploy.server')->with('ultimaPorta', $ultimaPorta)->with('vms', $vms)->with('clientes', $clientes);
            case 'EscalaSwarm':
                return view('deploy.swarm');
            case 'EscalaWeb':
                return view('deploy.web');
            case 'EscalaWebService':
                return view('deploy.ws');
            case 'rdp':
                return view('deploy.rdp');
                
        }
    }

    public function server(Request $request) {
        $ultimaPorta = $request->input('ultimaPorta');
        $cliente = $request->input('cliente');
        $vm = $request->input('vm');

        $clienteDados = $this->clienteRepository->findById($cliente);
        $dados = $this->vmRepository->getById($vm);

        $dado = [
            'ultimaPorta' => $ultimaPorta,
            'cliente' => $cliente,
            'vm' => $vm,
        ];

        $taskId = $this->asyncTasksRepository->create('DeployServer', $dado);

        $idServico = $this->servicosRepository->getIdByNome('EscalaServer');
        
        Server::dispatch($ultimaPorta, $clienteDados, $vm, $taskId, $dados, $idServico);
        return redirect('/deploy');
    }
}
