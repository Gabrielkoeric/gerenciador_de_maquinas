<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

use App\Jobs\ManipulaUsuario\Reset;

use App\Repositories\ConfigGeral\ConfigGeralRepository;
use App\Repositories\AsyncTasks\AsyncTasksRepository;

use App\Services\AnsibleInventoryService;

class SecaoCloudController extends Controller
{
    protected ConfigGeralRepository $configRepo;
    protected AnsibleInventoryService $inventoryService;
    protected AsyncTasksRepository $asyncTasksRepository;

    public function __construct(
        ConfigGeralRepository $configRepo,
        AnsibleInventoryService $inventoryService,
        AsyncTasksRepository $asyncTasksRepository
    ) {
        $this->configRepo = $configRepo;
        $this->inventoryService = $inventoryService;
        $this->asyncTasksRepository = $asyncTasksRepository;
    }
  
    public function index(Request $request)
{
    $filtroClientes = $request->input('clientes', []);

    $query = DB::table('secao_cloud')
        ->join('cliente_escala', 'secao_cloud.id_cliente_escala', '=', 'cliente_escala.id_cliente_escala')
        ->select(
            'secao_cloud.id_secao_cloud',
            'secao_cloud.usuario',
            'secao_cloud.senha',
            'cliente_escala.nome as nome_cliente'
        )
        ->orderBy('cliente_escala.nome', 'asc')
        ->orderBy('secao_cloud.usuario', 'asc');

    // Aplica o filtro ANTES do get()
    if (!empty($filtroClientes)) {
        $query->whereIn('cliente_escala.id_cliente_escala', $filtroClientes);
    }

    $dados = $query->get();

    $todosClientes = DB::table('cliente_escala')->orderBy('nome')->get();
    
    return view('secao_cloud.index', [
        'dados' => $dados,
        'todosClientes' => $todosClientes,
        'filtroClientes' => $filtroClientes
    ]);
}

    public function create()
    {
        $clientes = DB::table('cliente_escala')
            ->select('id_cliente_escala', 'nome')
            ->get();
        return view('secao_cloud.create')->with('clientes', $clientes);
    }

    public function store(Request $request)
    {
        $usuario = $request->input('usuario');
        $senha = $request->input('senha');
        $cliente = $request->input('cliente');

        $dados = [
            'usuario' => $usuario,
            'senha' => $senha,
            'id_cliente_escala' => $cliente,
        ];

        DB::table('secao_cloud')->insertGetId($dados);

        return redirect('/secao_cloud');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        
        $clientes = DB::table('cliente_escala')
            ->select('id_cliente_escala', 'nome')
            ->get();

            $clienteAtual = DB::table('secao_cloud')
            ->join('cliente_escala', 'secao_cloud.id_cliente_escala', '=', 'cliente_escala.id_cliente_escala')
            ->where('id_secao_cloud', $id)
            ->select('secao_cloud.*', 'cliente_escala.nome as nome_cliente')
            ->first();
        

        return view('secao_cloud.edit')->with('clientes', $clientes)->with('clienteAtual', $clienteAtual);
        
    }

    public function update(Request $request, $id)
    {
        DB::table('secao_cloud')
            ->where('id_secao_cloud', $id)
            ->update([
                'usuario' => $request->usuario,
                'senha' => $request->senha,
                'id_cliente_escala' => $request->cliente,
            ]);
        return redirect('/secao_cloud');
    }

    public function destroy($id)
{
    DB::table('secao_cloud')->where('id_secao_cloud', $id)->delete();
    
    return redirect()->route('secao_cloud.index')
        ->with('mensagemSucesso', 'Registro excluído com sucesso!');
}

    public function resetar($id)
    {

        $id_vm = $this->configRepo->getConfigGeral('id_ad_clientes');

        $novaSenha = substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*', 8)), 0, 8);

        DB::table('secao_cloud')
            ->where('id_secao_cloud', $id)
            ->update(['senha' => $novaSenha]);

        $arquivo = $this->inventoryService->gerarInventory($id_vm);

        $usuario = DB::table('secao_cloud')
            ->where('id_secao_cloud', $id)
            ->value('usuario');

        $dado = [
            'id_vm' => $id_vm,
            'usuario' => $usuario,
            'novaSenha' => $novaSenha,
            'arquivo' => $arquivo
        ];

        $taskId = $this->asyncTasksRepository->create('Reset Pass', $dado);

        Reset::dispatch($usuario, $novaSenha, $arquivo, $taskId);

        return redirect()->route('secao_cloud.index');
    }

    public function api(Request $request)
    {
        // validação básica
        $validated = $request->validate([
            'usuario' => 'required|string|max:255',
            'senha'   => 'required|string|max:255',
            'cliente' => 'required|integer|exists:cliente_escala,id_cliente_escala',
        ]);

        // dados que serão inseridos
        $dados = [
            'usuario' => $validated['usuario'],
            'senha'   => $validated['senha'],
            'id_cliente_escala' => $validated['cliente'],
        ];

        // insere no banco e pega o id
        $id = DB::table('secao_cloud')->insertGetId($dados);

        // retorna resposta em JSON
        return response()->json([
            'message' => 'Registro inserido com sucesso',
        ], 201);
    }

    public function usuarios_logados(): JsonResponse
    {
    
     $ultimoRegistro = DB::table('horario_auditoria')
        ->orderBy('id_horario_auditoria', 'desc')
        ->first();

    $totalUsuarios = DB::table('auditoria_secao')
        ->where('id_horario_auditoria', $ultimoRegistro->id_horario_auditoria)
        ->sum('quantidade');

    return response()->json([
        [
            'total' => $totalUsuarios
        ]
    ]);

    }
}
