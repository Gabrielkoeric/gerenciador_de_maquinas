<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IpLanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $vms = \DB::table('ip_lan')
        ->join('vm', 'ip_lan.id_ip_lan', '=', 'vm.id_ip_lan')
        ->select(
            'ip_lan.id_ip_lan',
            'ip_lan.ip',
            'vm.nome as nomeServidor'
        );

    $servidores = \DB::table('ip_lan')
        ->join('servidor_fisico', 'ip_lan.id_ip_lan', '=', 'servidor_fisico.id_ip_lan')
        ->select(
            'ip_lan.id_ip_lan',
            'ip_lan.ip',
            'servidor_fisico.nome as nomeServidor'
        );

    $semVinculo = \DB::table('ip_lan')
        ->leftJoin('vm', 'ip_lan.id_ip_lan', '=', 'vm.id_ip_lan')
        ->leftJoin('servidor_fisico', 'ip_lan.id_ip_lan', '=', 'servidor_fisico.id_ip_lan')
        ->whereNull('vm.id_ip_lan')
        ->whereNull('servidor_fisico.id_ip_lan')
        ->select(
            'ip_lan.id_ip_lan',
            'ip_lan.ip',
            \DB::raw("NULL as nomeServidor")
        );

    $union = $vms->unionAll($servidores)->unionAll($semVinculo);

    $ips = \DB::table(\DB::raw("({$union->toSql()}) as sub"))
        ->mergeBindings($union)
        ->orderBy('id_ip_lan', 'asc')
        ->get();

    return view('iplan.index', compact('ips'));
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
}
