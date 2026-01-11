<?php

namespace App\Repositories\Cliente;

use Illuminate\Support\Facades\DB;
use App\Services\SqlMonitorService;

class ClienteRepository
{
    /*
    public function listarResumo()
    {
        //SqlMonitorService::start('CLI', 'SELECT');

        return DB::table('cliente_escala')
            ->select('id_cliente_escala as id', 'apelido')
            ->orderBy('apelido')
            ->get();
    }
*/
    public function listarCompleto()
    {
        SqlMonitorService::start(1, 1);

        return DB::table('cliente_escala as cliente')
            ->select('*')
            ->get();
    }
}
