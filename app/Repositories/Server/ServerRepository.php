<?php

namespace App\Repositories\Server;

use Illuminate\Support\Facades\DB;

class ServerRepository
{
    public function listarServidores()
    {
        return DB::table('servidor_fisico')
            ->join(
                'ip_lan',
                'ip_lan.id_ip_lan',
                '=',
                'servidor_fisico.id_ip_lan'
            )
            ->select(
                'servidor_fisico.id_servidor_fisico',
                'servidor_fisico.nome',
                'ip_lan.ip as ip_lan',
                'servidor_fisico.porta'
            )
            ->orderBy('servidor_fisico.nome', 'asc')
            ->get();
    }
}