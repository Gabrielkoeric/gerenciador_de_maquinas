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
        ->join('vm', 'servico_vm.id_vm', '=', 'vm.id_vm')
        ->join('servico', 'servico_vm.id_servico', '=', 'servico.id_servico')
        ->join('cliente_escala', 'servico_vm.id_cliente_escala', '=', 'cliente_escala.id_cliente_escala')
        ->select(
            'servico_vm.*',
            'vm.nome as nome_vm',
            'servico.nome as nome_servico',
            'cliente_escala.nome as nome_cliente'
        )
        ->get();

        return view('vmservico.index')->with('servicos', $servicos);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function executarComando(Request $request)
     {  
         $servicos = $request->input('servicos');
         Log::info('Serviços selecionados:', $servicos);
     
         $acao = $request->input('acao');
         Log::info('Ação selecionada: ' . $acao);
     
         foreach ($servicos as $id_servico_vm) {
             Log::info('Processando serviço ID: ' . $id_servico_vm);
     
             // 1. Buscar os dados da VM
             $vm = DB::table('vm')
                ->join('servico_vm', 'servico_vm.id_vm', '=', 'vm.id_vm')
                ->where('servico_vm.id_servico_vm', $id_servico_vm)
                ->select('vm.*') // ou 'vm.*', 'servico_vm.porta', etc.
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

             $servico_nome = DB::table('servico_vm')
                ->where('id_servico_vm', $id_servico_vm)
                ->value('nome');

            Log::info("serviço nome $servico_nome");

             $comando_completo = str_replace('{servico}', $servico_nome, $comando);
             Log::info("comando completo $comando_completo");
     
             if (!$comando) {
                 Log::warning("Nenhum comando encontrado para ação '$acao' e tipo '{$vm->tipo}'");
                 continue;
             }
     
             // 4. Executar o comando na VM
             if ($vm->tipo === 'ssh') {
                 Log::info("Executando comando via SSH na VM {$vm->iplan}");
                 $resultado = $this->executarComandoLinux($vm->iplan, $vm->porta, $usuarioVM->usuario, $usuarioVM->senha, $comando_completo);
                 Log::info("ver qual comando executa $comando_completo");
             } elseif ($vm->tipo === 'rdp') {
                 Log::info("Executando comando via RDP na VM {$vm->iplan}");
                 $resultado = $this->executarComandoWindows($vm->iplan, $usuarioVM->usuario, $usuarioVM->senha, $servico_nome, $vm->dominio, $acao, $id_servico_vm);
             }
     
             Log::info("Resultado da execução: " . ($resultado ?? 'Erro na execução'));
         }
     
         Log::info('Execução finalizada');
         return back()->with('mensagemSucesso', 'Comandos executados com sucesso.');
         
     }
    
      
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
     
      private function executarComandoWindows($iplan, $usuario, $senha, $servico, $dominio, $acao, $id_servico_vm)
{

    Log::info("Iniciando execução do comando via Ansible para IP: {$iplan}, serviço: {$servico}, ação: {$acao}");

    $dir = storage_path('app/public/scripty');
    $hostsFile = $dir . '/hosts';

    if (!file_exists($dir)) {
        mkdir($dir, 0775, true);
        Log::info("Diretório {$dir} criado.");
    } else {
        Log::info("Diretório {$dir} já existe.");
    }

// Verifica se a máquina está no domínio
if (!empty($dominio)) {
    $usuarioCompleto = "{$usuario}@{$dominio}";
    $transporte = "ntlm";
} else {
    $usuarioCompleto = $usuario;
    $transporte = "basic";
}

$conteudo = <<<EOT
[windows]
{$iplan}

[windows:vars]
ansible_user={$usuarioCompleto}
ansible_password={$senha}
ansible_port=5985
ansible_connection=winrm
ansible_winrm_transport={$transporte}
ansible_winrm_server_cert_validation=ignore
EOT;

    file_put_contents($hostsFile, $conteudo);
    Log::info("Arquivo de hosts salvo em: {$hostsFile}");
    Log::info("Conteúdo do arquivo de hosts:\n{$conteudo}");
    Log::info("a ação é :$acao");
    Log::info("o servico é :$servico");

    // Define o nome do playbook com base na ação
    switch (strtolower($acao)) {
        case 'start':
            $playbookName = 'inicia_servico.yml';
            break;
        case 'stop':
            $playbookName = 'para_servico.yml';
            break;
        case 'restart':
            $playbookName = 'reinicia.yml';
            break;
        case 'status':
            $playbookName = 'status_servico.yml';
            break;
        default:
            Log::error("Ação inválida: {$acao}");
            return "Ação inválida: {$acao}";
    }
    $playbook = $dir . '/' . $playbookName;
    Log::info("Playbook selecionado: {$playbook}");

    if (!file_exists($playbook)) {
        Log::error("Playbook não encontrado: {$playbook}");
        return "Playbook não encontrado: {$playbook}";
    }

    $cmd = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i " . escapeshellarg($hostsFile) .
           " " . escapeshellarg($playbook) .
           " --extra-vars " . escapeshellarg("servico={$servico}");

    Log::info("Comando montado para execução: {$cmd}");

    $saida = shell_exec($cmd);
    Log::info("Saída do comando:\n" . $saida);

    ////gravar log
    $status = 'sucesso';
if (
    str_contains($saida, 'unreachable=1') ||
    str_contains($saida, 'failed=1') ||
    str_contains($saida, 'UNREACHABLE') ||
    str_contains($saida, 'FAILED') ||
    empty($saida)
) {
    $status = 'falha';
}

$erro = null;
if ($status === 'falha') {
    if (preg_match('/"msg"\s*:\s*"([^"]+)"/', $saida, $matches)) {
        $erro = $matches[1];
    } elseif (preg_match('/msg=(.*)/', $saida, $matches)) {
        $erro = $matches[1];
    } else {
        $erro = 'Erro desconhecido';
    }
}

DB::table('logs_execucoes')->insert([
    'acao'          => $acao,
    'playbook'      => $playbookName ?? null,
    'comando'       => $cmd ?? null,
    'saida'         => $saida ?? null,
    'status'        => $status,
    'erro'          => $erro,
    'executado_em'  => now(),
    'created_at'    => now(),
    'updated_at'    => now(),
    'id'            => auth()->id(),
    'id_servico_vm' => $id_servico_vm,
]);
///////////////////

    return trim($saida);
}

    public function create()
    {
        $vms = DB::table('vm')
            ->select('id_vm', 'nome')
            ->get();

        $servicos = DB::table('servico')
            ->select('id_servico', 'nome')
            ->get();
        
        $clientes = DB::table('cliente_escala')
            ->select('id_cliente_escala', 'nome')
            ->get();

        return view('vmservico.create')->with('vms', $vms)->with('servicos', $servicos)->with('clientes', $clientes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nome = $request->input('nome');
        $porta = $request->input('porta');
        $vm = $request->input('vm');
        $servico = $request->input('servico');
        $cliente = $request->input('cliente');

        $dados = [
            'nome' => $nome,
            'porta' => $porta,
            'id_vm' => $vm,
            'id_servico' => $servico,
            'id_cliente_escala' => $cliente,
        ];

        DB::table('servico_vm')->insertGetId($dados);

        return redirect('/vm_servico');
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
        $vms = DB::table('vm')
            ->select('id_vm', 'nome')
            ->get();

        $servicos = DB::table('servico')
            ->select('id_servico', 'nome')
            ->get();
        
        $clientes = DB::table('cliente_escala')
            ->select('id_cliente_escala', 'nome')
            ->get();

            $dadosAtuais = DB::table('servico_vm')
            ->join('vm', 'vm.id_vm', '=', 'servico_vm.id_vm')
            ->join('servico', 'servico.id_servico', '=', 'servico_vm.id_servico')
            ->join('cliente_escala', 'cliente_escala.id_cliente_escala', '=', 'servico_vm.id_cliente_escala')
            ->where('servico_vm.id_servico_vm', $id)
            ->select(
                'servico_vm.*',
                'vm.id_vm', 'vm.nome as nome_vm',
                'servico.id_servico', 'servico.nome as nome_servico',
                'cliente_escala.id_cliente_escala', 'cliente_escala.nome as nome_cliente'
            )
            ->first();

        return view('vmservico.edit')->with('vms', $vms)->with('servicos', $servicos)->with('clientes', $clientes)->with('dadosAtuais', $dadosAtuais);
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
        $nome = $request->input('nome');
        $porta = $request->input('porta');
        $vm = $request->input('vm');
        $servico = $request->input('servico');
        $cliente = $request->input('cliente');

        $dados = [
            'nome' => $nome,
            'porta' => $porta,
            'id_vm' => $vm,
            'id_servico' => $servico,
            'id_cliente_escala' => $cliente,
            'updated_at' => now(), // já que usa timestamps, pode atualizar esse campo também
        ];

        DB::table('servico_vm')
            ->where('id_servico_vm', $id)
            ->update($dados);

        return redirect('/vm_servico');
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
