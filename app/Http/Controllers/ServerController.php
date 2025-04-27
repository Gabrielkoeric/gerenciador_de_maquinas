<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*$servers = DB::table('servidor_fisico')->get();

        return view('servers.index')->with('servers', $servers);*/

        $servers = DB::table('servidor_fisico')
        ->leftJoin('usuario_servidor_fisico', 'servidor_fisico.id_servidor_fisico', '=', 'usuario_servidor_fisico.id_servidor_fisico')
        ->select('servidor_fisico.*', 'usuario_servidor_fisico.usuario', 'usuario_servidor_fisico.senha')
        ->get();

        return view('servers.index')->with('servers', $servers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('servers.create');
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
        $ipwan = $request->input('ipwan');
        $iplan = $request->input('iplan');
        $porta = $request->input('porta');
        $dominio = $request->input('dominio');
        $tipo = $request->input('tipo');
        $usuario = $request->input('usuario');
        $senha = $request->input('senha');

        $dados = [
            'nome' => $nome,
            'ipwan' => $ipwan,
            'iplan' => $iplan,
            'porta' => $porta,
            'dominio' => $dominio,
            'tipo' => $tipo,
        ];
        $id = DB::table('servidor_fisico')->insertGetId($dados);

        $dados2= [
            
            'id_servidor_fisico' => $id,
            'usuario' => $usuario,
            'senha' => $senha,
        ];
        DB::table('usuario_servidor_fisico')->insertGetId($dados2);

        return redirect('/server')->with('mensagem.sucesso', 'Usuario inserido com sucesso!');
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
        $dados = DB::table('servidor_fisico')
        ->join('usuario_servidor_fisico', 'servidor_fisico.id_servidor_fisico', '=', 'usuario_servidor_fisico.id_servidor_fisico')
        ->where('servidor_fisico.id_servidor_fisico', $id)
        ->select('servidor_fisico.*', 'usuario_servidor_fisico.usuario', 'usuario_servidor_fisico.senha')
        ->first();

        //dd($dados);
        return view('servers.edit')->with('dados', $dados);
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
       // Atualizar a tabela servidor_fisico
    DB::table('servidor_fisico')
    ->where('id_servidor_fisico', $id)
    ->update([
        'nome' => $request->nome,
        'ipwan' => $request->ipwan,
        'iplan' => $request->iplan,
        'porta' => $request->porta,
        'dominio' => $request->dominio,
        'tipo' => $request->tipo,
        'updated_at' => now(),
    ]);

// Atualizar a tabela usuario_servidor_fisico
DB::table('usuario_servidor_fisico')
    ->where('id_servidor_fisico', $id)
    ->update([
        'usuario' => $request->usuario,
        'senha' => $request->senha, 
        'updated_at' => now(),
    ]);

    return redirect('/server');

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

    public function executarComando(Request $request)
    {
    $servidores = DB::table('servidor_fisico as s')
                    ->join('usuario_servidor_fisico as u', 's.id_servidor_fisico', '=', 'u.id_servidor_fisico')
                    ->where('s.tipo', 'rdp')
                    ->select('s.*', 'u.usuario', 'u.senha')
                    ->get();

        foreach ($servidores as $server) {
            // Aqui você executa o comando Windows para cada servidor
            $resultado = $this->executarComandoWindows($server->iplan, $server->usuario, $server->senha, $server->dominio);  
        
            // Log para acompanhar o resultado
            Log::info("Resultado: $resultado");
            //Log::info("Resultado: " . json_encode($server));
        }

        return redirect('/server')->with('success', 'Comando executado com sucesso!');
    }

    private function executarComandoWindows($iplan, $usuario, $senha, $dominio)
    {

    Log::info("Iniciando execução do comando via Ansible para IP: {$iplan}, serviço: {$usuario}, ação: {$dominio}");

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

    $playbookName = 'listar_vms_hyperv.yml';
     
    $playbook = $dir . '/' . $playbookName;
    Log::info("Playbook selecionado: {$playbook}");

    if (!file_exists($playbook)) {
        Log::error("Playbook não encontrado: {$playbook}");
        return "Playbook não encontrado: {$playbook}";
    }

    $cmd = "ANSIBLE_HOST_KEY_CHECKING=False ansible-playbook -i " . escapeshellarg($hostsFile) .
           " " . escapeshellarg($playbook);

    Log::info("Comando montado para execução: {$cmd}");

    $saida = shell_exec($cmd);
    Log::info("Saída do comando:\n" . $saida);
/*
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
///////////////////*/

    return trim($saida);
}

}
