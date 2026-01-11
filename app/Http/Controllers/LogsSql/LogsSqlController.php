<?php

namespace App\Http\Controllers\LogsSql;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\LogSql\LogSqlRepository;

class LogsSqlController extends Controller
{

    protected LogSqlRepository $logSqlRepository;

    public function __construct(LogSqlRepository $logSqlRepository)
    {
        $this->logSqlRepository = $logSqlRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(logSqlRepository $logSqlRepository)
    {
        $logsSql = $logSqlRepository->listarCompleto();

        return view('logsSql.index', compact('logsSql'));
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

    public function clear()
    {
        $this->logSqlRepository->clear();

        return redirect()
            ->route('logs_sql.index')
            ->with('success', 'Logs SQL apagados com sucesso.');
    }
}
