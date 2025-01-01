<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpseclib3\Net\SSH2;

class SSHController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function ssh(Request $request, $id_server)
    {
        $server = DB::table('server')->where('id_server', $id_server)->first();

        $hostname = $server->ip_lan;
        $username = $server->usuario;
        $password = $server->senha;

        $ssh = new SSH2($hostname);
        if (!$ssh->login($username, $password)) {
            return 'Login falhou';
        }

        // Obtenha o comando do formulário
        $command = $request->input('command', 'ls');

        // Execute o comando
        $output = $ssh->exec($command);

        // Retorne a visão com o resultado
        return view('servers.ssh', ['output' => $output, 'server' => $server]);
    }

    public function index()
    {
        //
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
