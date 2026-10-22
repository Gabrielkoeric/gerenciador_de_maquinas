<?php

namespace App\Http\Controllers\Bkp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\Cliente\ClienteRepository;
use App\Repositories\Usuario\UsuarioRepository;
use App\Repositories\PermissaoBkpCliente\PermissaoBkpClienteRepository;

class permissaoBkpController extends Controller
{
    protected $clienteRepository;
    protected $usuarioRepository;
    protected $permissaoBkpClienteRepository;

    public function __construct(
        ClienteRepository $clienteRepository,
        UsuarioRepository $usuarioRepository,
        PermissaoBkpClienteRepository $permissaoBkpClienteRepository
    ) {
        $this->clienteRepository = $clienteRepository;
        $this->usuarioRepository = $usuarioRepository;
        $this->permissaoBkpClienteRepository = $permissaoBkpClienteRepository;
    }

    public function index()
    {
        $permissoes = $this->permissaoBkpClienteRepository->listarCompleto();

        return view('permissao_bkp.index', compact('permissoes'));
    }

    public function create()
    {
        $usuarios = $this->usuarioRepository->listarCompleto();
        $clientes = $this->clienteRepository->listarCompleto();

        return view('permissao_bkp.create', compact('usuarios', 'clientes'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'numeroSd' => 'required|string|max:50',
            'email' => 'required|exists:usuarios,id',
            'cliente' => 'required|exists:cliente_escala,id_cliente_escala',
        ]);
    
        $this->permissaoBkpClienteRepository->create([
            'ano_referencia' => date('Y'),
            'numero_sd' => $dados['numeroSd'],
            'data_liberacao' => now(),
            'id_cliente_escala' => $dados['cliente'],
            'id_usuario' => $dados['email'],
            'id_usuario_liberacao' => auth()->id(),
        ]);
    
        return redirect()
            ->route('permissao_bkp.index')
            ->with('success', 'Permissão de backup liberada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
