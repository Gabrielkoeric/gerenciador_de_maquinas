<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogsCheckController extends Controller
{
    public function index(Request $request)
    {
        $resultCheckins = DB::table('check_logs as logs')
            ->select('portaria.email as portaria', 'usuario.email', 'logs.created_at', 'logs.ip_address')
            ->join('usuarios as portaria', 'logs.id', '=', 'portaria.id')
            ->join('controle_ingressos', 'logs.id_controle_ingresso', '=', 'controle_ingressos.id_controle_ingresso')
            ->join('usuarios as usuario', 'controle_ingressos.id', '=', 'usuario.id')
            ->where('logs.checkIn', 1)
            ->paginate(100);
        $resultCheckouts = DB::table('check_logs as logs')
            ->select('portaria.email as portaria', 'usuario.email', 'logs.created_at', 'logs.ip_address')
            ->join('usuarios as portaria', 'logs.id', '=', 'portaria.id')
            ->join('controle_ingressos', 'logs.id_controle_ingresso', '=', 'controle_ingressos.id_controle_ingresso')
            ->join('usuarios as usuario', 'controle_ingressos.id', '=', 'usuario.id')
            ->where('logs.checkOut', 1)
            ->paginate(100);

        return view('LogsCheck.index')->with('resultCheckins', $resultCheckins)->with('resultCheckouts', $resultCheckouts);
    }
}
