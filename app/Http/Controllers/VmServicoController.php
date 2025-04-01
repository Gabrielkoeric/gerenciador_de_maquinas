<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;

class VmServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $servicos = DB::table('servico_vm')
    ->join('servico', 'servico_vm.id_servico', '=', 'servico.id_servico')
    ->join('vm', 'servico_vm.id_vm', '=', 'vm.id_vm')
    ->join('cliente_escala', 'servico_vm.id_cliente_escala', '=', 'cliente_escala.id_cliente_escala')
    ->select(
        'servico_vm.id_servico_vm',
        'servico.nome as servico_nome',
        'vm.nome as vm_nome',
        'cliente_escala.nome as cliente_nome',
        'servico_vm.porta',
        'servico_vm.tipo'
    )->get();

        return view('vmservico.index')->with('servicos', $servicos);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function executarAcao(Request $request)
     {
         $servicos = $request->input('servicos');
         Log::info('Serviços selecionados:', $servicos);

         $servico_nome = DB::table('servico')
          ->where('id_servico', $servicos)
          ->value('nome');
          
          Log::info('Nome do serviço selecionado:', ['servico_nome' => $servico_nome]);

     
         $acao = $request->input('acao');
         Log::info('Ação selecionada: ' . $acao);
     
         foreach ($servicos as $id_servico_vm) {
             Log::info('Processando serviço ID: ' . $id_servico_vm);
     
             // 1. Buscar os dados da VM
             $vm = DB::table('vm')
                 ->join('servico_vm', 'vm.id_vm', '=', 'servico_vm.id_vm')
                 ->where('servico_vm.id_servico_vm', $id_servico_vm)
                 ->select('vm.ip_lan', 'vm.porta', 'vm.tipo', 'vm.id_vm')
                 ->first();
             
             Log::info('Dados da VM:', (array) $vm);
     
             if (!$vm) {
                 Log::warning("Nenhuma VM encontrada para serviço ID: $id_servico_vm");
                 continue;
             }
     
             // 2. Buscar o usuário e senha da VM
             $usuarioVM = DB::table('usuario_vm')
                 ->where('id_vm', $vm->id_vm)
                 ->select('usuario', 'senha')
                 ->first();
             
             Log::info('Usuário da VM:', (array) $usuarioVM);
     
             if (!$usuarioVM) {
                 Log::warning("Nenhum usuário encontrado para VM ID: {$vm->id_vm}");
                 continue;
             }
     
             // 3. Buscar o comando na tabela comando_execucao_remoto
             $comando = DB::table('comando_execucao_remota')
                 ->where('acao', $acao)
                 ->where('tipo', $vm->tipo) // Filtra pelo tipo (rdp ou ssh)
                 ->value('comando');
     
             Log::info("Comando para ação '$acao' e tipo '{$vm->tipo}': " . ($comando ?? 'NÃO ENCONTRADO'));

             $comando_completo = str_replace('{servico}', $servico_nome, $comando);
             Log::info("comando completo $comando_completo");
     
             if (!$comando) {
                 Log::warning("Nenhum comando encontrado para ação '$acao' e tipo '{$vm->tipo}'");
                 continue;
             }
     
             // 4. Executar o comando na VM
             if ($vm->tipo === 'ssh') {
                 Log::info("Executando comando via SSH na VM {$vm->ip_lan}");
                 $resultado = $this->executarComandoLinux($vm->ip_lan, $vm->porta, $usuarioVM->usuario, $usuarioVM->senha, $comando_completo);
                 Log::info("ver qual comando executa $comando_completo");
             } elseif ($vm->tipo === 'rdp') {
                 Log::info("Executando comando via RDP na VM {$vm->ip_lan}");
                 $resultado = $this->executarComandoWindows($vm->ip_lan, $usuarioVM->usuario, $usuarioVM->senha, $comando_completo);
             }
     
             Log::info("Resultado da execução: " . ($resultado ?? 'Erro na execução'));
         }
     
         Log::info('Execução finalizada');
         return back()->with('mensagemSucesso', 'Comandos executados com sucesso.');
     }
     
     /**
      * Executa comando remoto via SSH em Linux
      */
     private function executarComandoLinux($ip, $porta, $usuario, $senha, $comando)
     {
         Log::info("Conectando via SSH em $ip:$porta com usuário $usuario");
         
         $connection = ssh2_connect($ip, $porta);
         
         if (!$connection) {
             Log::error("Falha ao conectar via SSH em $ip:$porta");
             return false;
         }
     
         ssh2_auth_password($connection, $usuario, $senha);
         
         $stream = ssh2_exec($connection, $comando);
         
         if (!$stream) {
             Log::error("Falha ao executar comando SSH em $ip");
             return false;
         }
     
         stream_set_blocking($stream, true);
         $output = stream_get_contents($stream);
         fclose($stream);
         
         Log::info("Saída do comando SSH: " . trim($output));
         
         return $output;
     }
     
     /**
      * Executa comando remoto via PowerShell em Windows (RDP)
      */
     
    public function executarComandoWindows(Request $request)
    {
         // Variáveis fixas para teste
    $acao = 'status'; // Ou 'start', 'stop', 'restart'
    $ip_lan = '192.168.1.15'; // IP do destino
    $usuario = 'teste'; // Nome do usuário
    $senha = 'teste'; // Senha
    $servico = 'Spooler'; // Nome do serviço
    $dominio = ''; // Caso seja em domínio

    // Caminho absoluto do script Python
    $scriptPath = '/var/www/html/gerenciador_de_maquinas/storage/scripty/executa_windows.py';

    // Monta o comando para chamar o script Python
    $comando = "python3 " . escapeshellarg($scriptPath) . " "
        . escapeshellarg($ip_lan) . " "
        . escapeshellarg($usuario) . " "
        . escapeshellarg($senha) . " "
        . escapeshellarg($servico) . " "
        . escapeshellarg($acao) . " "
        . escapeshellarg($dominio);

    // Executa o script e captura a saída
    $saida = shell_exec($comando);

    // Retorna o resultado
    return response()->json(['resultado' => trim($saida)]);
    }
      
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
