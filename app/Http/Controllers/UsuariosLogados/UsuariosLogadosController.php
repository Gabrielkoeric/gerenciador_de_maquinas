<?php

namespace App\Http\Controllers\UsuariosLogados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UsuariosLogados\UsuariosLogadosRepository;

class UsuariosLogadosController extends Controller
{
    protected UsuariosLogadosRepository $usuariosLogados;

    public function __construct(
        UsuariosLogadosRepository $usuariosLogados
    ) {
        $this->usuariosLogados = $usuariosLogados;
    }
    
    public function index(Request $request)
    {
        $usuariosLogados = $this->usuariosLogados->countLogados();

        return view('usuarios_logados.index')->with('usuariosLogados', $usuariosLogados);
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
