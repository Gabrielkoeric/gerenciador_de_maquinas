<?php

namespace App\Repositories\Cliente;

use Illuminate\Support\Facades\DB;
use App\Services\SqlMonitorService;

class ClienteRepository
{
    public function listarCompleto()
    {
        SqlMonitorService::start(1, 1);

        return DB::table('cliente_escala as cliente')
            ->select('*')
            ->get();
    }

    public function create(array $dados): int
    {
        SqlMonitorService::start(1, 2);

        return DB::table('cliente_escala')->insertGetId($dados);
    }

    public function findById(int $id)
    {
        SqlMonitorService::start(1, 1);

        return DB::table('cliente_escala')
            ->where('id_cliente_escala', $id)
            ->first();
    }

    public function update(int $id, array $dados): void
    {
        DB::table('cliente_escala')
            ->where('id_cliente_escala', $id)
            ->update($dados);
    }

    public function getClientesComRdp()
    {
        return DB::table('cliente_escala')
            ->select('apelido', 'porta_rdp')
            ->whereNotNull('apelido')
            ->whereNotNull('porta_rdp')
            ->get();
    }

    public function getClienteByApelido(string $apelido)
    {
        return DB::table('cliente_escala')
            ->where('apelido', $apelido)
            ->first();
    }

}
