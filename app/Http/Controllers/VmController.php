<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $vms = DB::table('vm')
    ->select(
        'vm.*',
        'ip_lan.ip as ip_lan_vm',
        'dominio.nome as dominio_nome',
        'dominio.usuario as dominio_usuario',
        'dominio.senha as dominio_senha',
        'servidor_fisico.nome as nome_servidor_fisico',
        'usuario_vm.usuario as usuario_local',
        'usuario_vm.senha as senha_local'
    )
    ->leftJoin('ip_lan', 'vm.id_ip_lan', '=', 'ip_lan.id_ip_lan')
    ->leftJoin('dominio', 'vm.id_dominio', '=', 'dominio.id_dominio')
    ->leftJoin('servidor_fisico', 'vm.id_servidor_fisico', '=', 'servidor_fisico.id_servidor_fisico')
    ->leftJoin('usuario_vm', function ($join) {
        $join->on('vm.id_vm', '=', 'usuario_vm.id_vm')
             ->where('usuario_vm.principal', '=', 1);
    })
    ->orderBy('vm.nome', 'asc')
    ->get();

    return view('vm.index')->with('vms', $vms);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $servidores = DB::table('servidor_fisico')
        ->select('id_servidor_fisico', 'nome')
        ->get();

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


        return view('vm.create')->with('servidores', $servidores)->with('ipslan', $ipslan);
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
        $iplan = $request->input('iplan');
        $porta = $request->input('porta');
        $dominio = $request->input('dominio');
        $tipo = $request->input('tipo');
        $so = $request->input('so');
        $usuario = $request->input('usuario');
        $senha = $request->input('senha');
        $servidor = $request->input('servidor');
        $autostart = $request->input('autostart', 0);

        $dados = [
            'nome' => $nome,
            'id_ip_lan' => $iplan,
            'porta' => $porta,
            'id_dominio' => $dominio,
            'tipo' => $tipo,
            'so' => $so,
            'id_servidor_fisico' => $servidor,
            'autostart' => $autostart,
        ];
        $id = DB::table('vm')->insertGetId($dados);

        $dados2= [
            'id_vm' => $id,
            'usuario' => $usuario,
            'senha' => $senha,
        ];
        DB::table('usuario_vm')->insertGetId($dados2);

        return redirect('/vm');
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
        $dados = DB::table('vm')
        ->leftJoin('usuario_vm', 'vm.id_vm', '=', 'usuario_vm.id_vm')
        ->select('vm.*', 'usuario_vm.usuario', 'usuario_vm.senha')
        ->where('vm.id_vm', $id)
        ->first();

        $servidores = DB::table('servidor_fisico')
        ->select('id_servidor_fisico', 'nome')
        ->get();

        $servidorAtual = DB::table('vm')
        ->join('servidor_fisico', 'vm.id_servidor_fisico', '=', 'servidor_fisico.id_servidor_fisico')
        ->where('vm.id_vm', $id)
        ->select('servidor_fisico.id_servidor_fisico', 'servidor_fisico.nome')
        ->first();

        $iplanAtual = null;
            if ($dados->id_ip_lan) {
            $iplanAtual = DB::table('ip_lan')->where('id_ip_lan', $dados->id_ip_lan)->first();
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

        //dd($servidorAtual);
        return view('vm.edit')
    ->with('dados', $dados)
    ->with('servidores', $servidores)
    ->with('servidorAtual', $servidorAtual)
    ->with('iplanAtual', $iplanAtual)
    ->with('ipslan', $ipslan);
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

       //dd($id);
    DB::table('vm')
    ->where('id_vm', $id)
    ->update([
        'nome' => $request->nome,
        'id_ip_lan' => $request->iplan,
        'porta' => $request->porta,
        'id_dominio' => $request->dominio,
        'tipo' => $request->tipo,
        'so' => $request->so,
        'autostart' => $request->input('autostart', 0),
        'id_servidor_fisico' => $request->servidor,
        'updated_at' => now(),
    ]);

// Atualizar a tabela usuario_servidor_fisico
DB::table('usuario_vm')
    ->where('id_vm', $id)
    ->update([
        'usuario' => $request->usuario,
        'senha' => $request->senha, 
        'updated_at' => now(),
    ]);

    return redirect('/vm');
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
