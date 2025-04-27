<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\BuscaVm;
use Carbon\Carbon;

class ExecutaComandoController extends Controller
{
    public function executarComando(Request $request)
    {
        $servidores = DB::table('servidor_fisico as s')
                    ->join('usuario_servidor_fisico as u', 's.id_servidor_fisico', '=', 'u.id_servidor_fisico')
                    ->where('s.tipo', 'rdp')
                    ->select('s.*', 'u.usuario', 'u.senha')
                    ->get();

        foreach ($servidores as $server) {
            // Define os parâmetros que quer armazenar (em JSON)
            $parametros = [
                'iplan' => $server->iplan,
                'usuario' => $server->usuario,
                'dominio' => $server->dominio ?? null, // caso não exista domínio
            ];

            // Cria o registro do task no banco
            $taskId = DB::table('async_tasks')->insertGetId([
                'nome_async_tasks' => 'BuscaVm',
                'horario_disparo' => Carbon::now(),
                'parametros' => json_encode($parametros),
                'status' => 'Pendente',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    
            // Dispara o Job passando o servidor e o ID da task
            //BuscaVm::dispatch($server, $taskId);
            BuscaVm::dispatch($server->iplan, $server->usuario, $server->senha, $server->dominio, $taskId);

        }
        return redirect('/server')->with('success', 'Comando executado com sucesso!');
    }
}
