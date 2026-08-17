<?php

namespace App\Http\Controllers\Replicacao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class replicacaoController extends Controller
{
    public function index()
    {
        $repositorios = DB::table('cliente_escala as c')
            ->leftJoin('repositorios as r', function ($join) {
                $join->on('r.id_cliente_escala', '=', 'c.id_cliente_escala')
                     ->where('r.tipo', '=', 'replicacao');
            })
            ->select(
                'r.sincronizado',
                'c.apelido',
                'r.origem',
                'r.destino',
                'r.log_dir',
                'r.diario'
            )
            ->orderBy('c.apelido')
            ->get();

        return view('repositoriosReplicacao.index')->with('repositorios', $repositorios);
    }

    public function diario()
    {
        $repositorios = DB::table('cliente_escala as c')
            ->leftJoin('repositorios as r', function ($join) {
                $join->on('r.id_cliente_escala', '=', 'c.id_cliente_escala')
                     ->where('r.tipo', '=', 'arquivo');
            })
            ->select(
                'r.sincronizado',
                'c.apelido',
                'r.origem',
                'r.destino',
                'r.log_dir',
                'r.diario'
            )
            ->orderBy('c.apelido')
            ->get();

        return view('repositoriosReplicacao.index')->with('repositorios', $repositorios);
    }    

    public function create()
    {
        $clientes = DB::table('cliente_escala as c')
            ->leftJoin('repositorios as r', function ($join) {
                $join->on('c.id_cliente_escala', '=', 'r.id_cliente_escala')
                     ->where('r.tipo', '=', 'replicacao');
            })
            ->whereNull('r.id_repositorios')
            ->select('c.*')
            ->orderBy('c.apelido')
            ->get();
    
        return view('repositoriosReplicacao.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        DB::table('repositorios')->insert([
            'tipo' => 'replicacao',
            'prioridade' => 1,
            'tipo_copia' => $request->tipo,
            'origem' => $request->origem,
            'destino' => $request->destino,
            'log_dir' => $request->log,
            'tags' => $request->tag,
            'ativo' => 0,
            'diario' => 1,
            'id_cliente_escala' => $request->cliente,
            'id_vm' => $request->idServerBkp,
            'id_server_bkp' => $request->idServerBkp,
        ]);

        return redirect()->route('repositorioreplicacao.index');
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
