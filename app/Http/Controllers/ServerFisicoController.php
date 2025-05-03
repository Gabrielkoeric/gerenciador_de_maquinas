<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServerFisicoController extends Controller
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
            ->leftJoin('ip_lan', 'servidor_fisico.id_ip_lan', '=', 'ip_lan.id_ip_lan')
            ->leftJoin('ip_wan', 'servidor_fisico.id_ip_wan', '=', 'ip_wan.id_ip_wan')
            ->select(
                'servidor_fisico.*',
                'usuario_servidor_fisico.usuario',
                'usuario_servidor_fisico.senha',
                'ip_lan.ip as ip_lan',
                'ip_wan.ip as ip_wan'
            )
            ->orderBy('servidor_fisico.nome')
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
    // Buscar o ID do IP '0.0.0.0'
    $ipZero = DB::table('ip_lan')->where('ip', '0.0.0.0')->first();

    $ipslan = DB::table('ip_lan')
        ->where(function ($query) use ($ipZero) {
            $query->where('ip_lan.ip', '0.0.0.0') // sempre permitir esse IP
                ->orWhereNotIn('id_ip_lan', function ($sub) {
                    $sub->select('id_ip_lan')->from('servidor_fisico')->whereNotNull('id_ip_lan');
                })
                ->whereNotIn('id_ip_lan', function ($sub) {
                    $sub->select('id_ip_lan')->from('vm')->whereNotNull('id_ip_lan');
                });
        })
        ->get();

    $ipswan = DB::table('ip_wan')->get();

        return view('servers.create')->with('ipswan', $ipswan)->with('ipslan', $ipslan);
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
            'id_ip_wan' => $ipwan,
            'id_ip_lan' => $iplan,
            'porta' => $porta,
            'id_dominio' => $dominio,
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
         // Dados principais do servidor
    $dados = DB::table('servidor_fisico')
    ->leftJoin('usuario_servidor_fisico', 'servidor_fisico.id_servidor_fisico', '=', 'usuario_servidor_fisico.id_servidor_fisico')
    ->leftJoin('ip_lan', 'servidor_fisico.id_ip_lan', '=', 'ip_lan.id_ip_lan')
    ->leftJoin('ip_wan', 'servidor_fisico.id_ip_wan', '=', 'ip_wan.id_ip_wan')
    ->where('servidor_fisico.id_servidor_fisico', $id)
    ->select(
        'servidor_fisico.*',
        'usuario_servidor_fisico.usuario',
        'usuario_servidor_fisico.senha',
        'ip_lan.ip as ip_lan_valor',
        'ip_wan.ip as ip_wan_valor'
    )
    ->first();

// IP LAN atual (pode ser null)
$iplanAtual = null;
if ($dados->id_ip_lan) {
    $iplanAtual = DB::table('ip_lan')->where('id_ip_lan', $dados->id_ip_lan)->first();
}

// IP WAN atual (pode ser null)
$ipwanAtual = null;
if ($dados->id_ip_wan) {
    $ipwanAtual = DB::table('ip_wan')->where('id_ip_wan', $dados->id_ip_wan)->first();
}

        $ipZero = DB::table('ip_lan')->where('ip', '0.0.0.0')->first();

        $ipslan = DB::table('ip_lan')
        ->where(function ($query) use ($ipZero) {
            $query->where('ip_lan.ip', '0.0.0.0') // sempre permitir esse IP
                ->orWhereNotIn('id_ip_lan', function ($sub) {
                    $sub->select('id_ip_lan')->from('servidor_fisico')->whereNotNull('id_ip_lan');
                })
                ->whereNotIn('id_ip_lan', function ($sub) {
                    $sub->select('id_ip_lan')->from('vm')->whereNotNull('id_ip_lan');
                });
        })
        ->get();

    $ipswan = DB::table('ip_wan')->get();

        //dd($dados);
       // return view('servers.edit')->with('dados', $dados)->with('ipswan', $ipswan)->with('ipslan', $ipslan);
       return view('servers.edit', compact('dados', 'ipswan', 'ipslan', 'iplanAtual', 'ipwanAtual'));
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
        'id_ip_wan' => $request->ipwan,
        'id_ip_lan' => $request->iplan,
        'porta' => $request->porta,
        'id_dominio' => $request->dominio,
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
}
