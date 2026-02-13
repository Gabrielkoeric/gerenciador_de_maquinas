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
use App\Jobs\Deploy\Swarm;
use App\Jobs\Deploy\Ws;

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
                $vms = $this->vmRepository->getByTipo('escalaswarm');
                $clientes = $this->clienteRepository->getClientesComApelido();
                return view('deploy.swarm')->with('vms', $vms)->with('clientes', $clientes);
            case 'EscalaWebService':
                $ultimaPorta = $this->vmServicoRepository->lastPort('WebService');
                $vms = $this->vmRepository->getByTipo('escalawebswervice');
                $clientes = $this->clienteRepository->getClientesComApelido();
                return view('deploy.ws')->with('vms', $vms)->with('clientes', $clientes)->with('ultimaPorta', $ultimaPorta);
            case 'rdp':
                return view('deploy.rdp');
            case 'EscalaWeb':
                return view('deploy.web');    
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

    public function swarm(Request $request) {
        $cliente = $request->input('cliente');
        $vm = $request->input('vm');
        $nome_servico = $request->input('nome');

        $escalaServer = $this->vmServicoRepository->getEscalaserverCliente($cliente);

        $clienteDados = $this->clienteRepository->findById($cliente);

        $dados = $this->vmRepository->getById($vm);

        $dado = [
            'nome_servico' => $nome_servico,
            'cliente' => $cliente,
            'vm_swarm' => $vm,
            'id_vm_aplicacao' => $escalaServer->id_vm,
            'porta' => $escalaServer->porta,
            'nome_vm_aplicacao' => $escalaServer->nome_vm,
            'clienteDados' => $clienteDados->apelido,
        ];

        $taskId = $this->asyncTasksRepository->create('DeploySwarm', $dado);

        Swarm::dispatch($clienteDados, $dados, $escalaServer, $nome_servico, $taskId);

        return redirect('/deploy');
    }

    public function ws(Request $request) {
        $cliente = $request->input('cliente');
        $vm = $request->input('vm');
        $nome_servico = $request->input('nome');
        $ultimaPorta = $request->input('ultimaPorta');

        $escalaServer = $this->vmServicoRepository->getEscalaserverCliente($cliente);

        $clienteDados = $this->clienteRepository->findById($cliente);

        $dados = $this->vmRepository->getById($vm);

        $dado = [
            'nome_servico' => $nome_servico,
            'cliente' => $cliente,
            'vm_swarm' => $vm,
            'id_vm_aplicacao' => $escalaServer->id_vm,
            'porta' => $escalaServer->porta,
            'nome_vm_aplicacao' => $escalaServer->nome_vm,
            'clienteDados' => $clienteDados->apelido,
        ];

        $taskId = $this->asyncTasksRepository->create('DeployWS', $dado);

        Ws::dispatch($ultimaPorta, $clienteDados, $dados, $escalaServer, $nome_servico, $taskId);

        return redirect('/deploy');
    }
}
