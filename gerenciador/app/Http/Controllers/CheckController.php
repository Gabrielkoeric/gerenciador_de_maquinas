<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckController extends Controller
{
    public function ingresso($hash)
    {
        $ingressoInfo = DB::table('controle_ingressos')
            ->where('hash', $hash)
            ->first();

        // Verificar se a hash existe no banco de dados
        if ($ingressoInfo) {
            //dd("tem a hash");
            $dados = DB::table('controle_ingressos')
                ->select('usuarios.nome_completo AS nome_do_usuario', 'ingressos.nome AS nome_do_ingresso', 'controle_ingressos.check-in as checkin', 'controle_ingressos.check-out as checkout')
                ->join('compra_ingresso', 'controle_ingressos.id_compra_ingresso', '=', 'compra_ingresso.id_compra_ingresso')
                ->join('ingressos', 'compra_ingresso.id_lote', '=', 'ingressos.id_ingressos')
                ->join('usuarios', 'controle_ingressos.id', '=', 'usuarios.id')
                ->where('controle_ingressos.hash', '=', $hash)
                ->get();

            return view('check.check')->with('dados', $dados)->with('hash', $hash);
        } else {
            abort(404);
        }
    }
    public function checkin(Request $request){
        $hash = $request->query('hash');

        DB::table('controle_ingressos')
            ->where('hash', $hash)
            ->update([
                'check-in' => false,
                'check-out' => true
            ]);
        $id_controle_ingresso = DB::table('controle_ingressos')
            ->where('hash', $hash)
            ->value('id_controle_ingresso');

        $usuario = Auth::user()->id;

        $dados = [
            'checkIn' => 1,
            'checkOut' => 0,
            'ip_address' => request()->ip(),
            'id' => $usuario,
            'id_controle_ingresso' => $id_controle_ingresso,
            'created_at' => now()
        ];
        DB::table('check_logs')->insert($dados);
        return to_route('home.index');
    }

    public function checkout(Request $request){
        $hash = $request->query('hash');

        DB::table('controle_ingressos')
            ->where('hash', $hash) // Substitua 'seu_valor_de_hash' pelo valor que você deseja filtrar
            ->update([
                'check-in' => true, // Define o valor de check-in para true
                'check-out' => false, // Define o valor de check-out para true
            ]);

        $id_controle_ingresso = DB::table('controle_ingressos')
            ->where('hash', $hash)
            ->value('id_controle_ingresso');

        $usuario = Auth::user()->id;

        $dados = [
            'checkIn' => 0,
            'checkOut' => 1,
            'ip_address' => request()->ip(),
            'id' => $usuario,
            'id_controle_ingresso' => $id_controle_ingresso,
            'created_at' => now()
        ];
        DB::table('check_logs')->insert($dados);
        return to_route('home.index');
    }

    public function index()
    {
        $usuario = Auth::user()->id;
        $sqls = DB::table('controle_ingressos')
            ->select('controle_ingressos.qrcode', 'ingressos.nome as nome_do_ingresso')
            ->join('compra_ingresso', 'controle_ingressos.id_compra_ingresso', '=', 'compra_ingresso.id_compra_ingresso')
            ->join('ingressos', 'compra_ingresso.id_lote', '=', 'ingressos.id_ingressos')
            ->where('controle_ingressos.id', $usuario)
            ->get();
        return view('check.index')->with('sqls', $sqls);
    }
}
