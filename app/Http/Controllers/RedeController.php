<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RedeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $redes = DB::table('rede')->get();

        return view('rede.index')->with('redes', $redes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rede.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $ip = $request->input('ip');
    $mascara = $request->input('mascara');
    $descricao = $request->input('descricao');

    // 1. Insere a rede e obtém o ID
    $idRede = DB::table('rede')->insertGetId([
        'ip' => $ip,
        'mascara' => $mascara,
        'descricao' => $descricao,
    ]);

    // 2. Converte IPs para long
    $ipLong = ip2long($ip);
    $maskLong = ip2long($mascara);

    // 3. Calcula o primeiro e último IP da rede
    $network = $ipLong & $maskLong;
    $broadcast = $network | (~$maskLong & 0xFFFFFFFF);

    // 4. Insere os IPs válidos (excluindo rede e broadcast)
    $ips = [];
    for ($i = $network + 1; $i < $broadcast; $i++) {
        $ips[] = [
            'ip' => long2ip($i),
            'id_rede' => $idRede,
        ];
    }

    DB::table('ip_lan')->insert($ips);

    return redirect('/rede');
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
