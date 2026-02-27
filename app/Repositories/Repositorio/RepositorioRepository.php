<?php

namespace App\Repositories\Repositorio;

use Illuminate\Support\Facades\DB;

class RepositorioRepository
{
    public function getRepositoriosDiariosArquivo()
    {
        return DB::table('repositorios')
            ->where('diario', 1)
            ->where('tipo', 'arquivo')
            ->orderBy('prioridade', 'asc')
            ->get();
    }

    public function buscarCompletoPorId(int $idRepositorio)
    {
        return DB::table('repositorios as r')
            ->join('vm as v', 'r.id_server_bkp', '=', 'v.id_vm')
            ->join('ip_lan as ip', 'v.id_ip_lan', '=', 'ip.id_ip_lan')
            ->join('cliente_escala as c', 'r.id_cliente_escala', '=', 'c.id_cliente_escala')
            ->select(
                'r.*',
                'ip.ip',
                'c.apelido'
            )
            ->where('r.id_repositorios', $idRepositorio)
            ->first();
    }
}