<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Jobs\Rclone\RcloneJob;

class RcloneExecucoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $repositorios = DB::table('repositorios')->get();

        return view('repositorios.index')->with('repositorios', $repositorios);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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


    public function executa(Request $request)
{
    // Busca os repositórios ativos do tipo "arquivo" ordenando pela prioridade
    $repositorios = DB::table('repositorios')
        ->where('ativo', 1)
        ->where('tipo', 'arquivo')
        ->orderBy('prioridade', 'asc')
        ->get();

    // Insere as execuções na ordem correta
    foreach ($repositorios as $repositorio) {
        DB::table('rclone_execucoes')->insert([
            'id_repositorio' => $repositorio->id_repositorios,
            'status' => 'pendente',
            'disparo' => Carbon::now(),
            'inicio' => null,
            'fim' => null,
            'qtd_arquivos_transferidos' => null,
            'qtd_arquivos_check' => null,
            'bytes_transferidos' => null,
            'log_path' => null,
            'erro' => null,
        ]);
    }

    $execucoesPendentes = DB::table('rclone_execucoes')
        ->where('status', 'pendente')
        ->orderBy('id_execucao')
        ->limit(3)
        ->get();

    foreach ($execucoesPendentes as $execucao) {
        RcloneJob::dispatch($execucao->id_execucao)->onQueue('rclone');
    }

    return redirect('/rclone')->with('success', 'Execuções criadas com sucesso!');
}

}
