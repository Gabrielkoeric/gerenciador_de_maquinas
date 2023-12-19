<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AccessLogsController extends Controller
{
    public function index(Request $request)
    {
        $accessLogs = DB::table('access_logs')
            ->join('usuarios', 'access_logs.id', '=', 'usuarios.id')
            ->select('access_logs.*', 'usuarios.email')
            ->orderBy('access_logs.id_access_logs', 'desc') // ou 'desc' para ordem decrescente
            ->paginate(30);

        return view('accessLogs.index')->with('accessLogs', $accessLogs);
    }

}
