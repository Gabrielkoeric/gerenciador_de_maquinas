<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigGeralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configGeral = DB::table('config_geral')->get();

        return view('configgeral.index')->with('configGeral', $configGeral);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('configgeral.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $nomeConfig = $request->input('nomeConfig');
        $valorConfig = $request->input('valorConfig');

        $dados = [
            'nomeConfig' => $nomeConfig,
            'valorConfig' => $valorConfig,
        ];

        DB::table('config_geral')->insertGetId($dados);

        return redirect('/config_geral');
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
        $config = DB::table('config_geral')->where('id_config_geral', $id)->first();

        //dd($comando);
        return view('configgeral.edit')->with('config', $config);
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
        DB::table('config_geral')
            ->where('id_config_geral', $id)
            ->update([
                'nomeConfig' => $request->nomeConfig,
                'valorConfig' => $request->valorConfig,
            ]);
        return redirect('/config_geral');
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
