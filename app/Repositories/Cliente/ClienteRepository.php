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

    public function getClientesComApelido()
    {
        return DB::table('cliente_escala')
            ->select('id_cliente_escala', 'apelido', 'nome')
            ->whereNotNull('apelido')
            ->where('apelido', '!=', '')
            ->orderBy('apelido') // ASC padrão
            ->get();
    }

    public function getClientesLicencas()
    {
        return DB::table('cliente_escala')
            ->select([
                'id_cliente_escala',
                'nome',
                'apelido',
                'licenca',
                'coletor',
                'desktop'
            ])
            ->whereNotNull('nome')
            ->whereNotNull('apelido')
            ->whereNotNull('licenca')
            ->whereNotNull('coletor')
            ->whereNotNull('desktop')
            ->where('licenca', '>', 0)
            ->orderBy('apelido')
            ->get();   
    }

    public function updateLicencas(int $id, int $coletor, int $desktop, int $licenca = null): bool
    {
        $total = $coletor + $desktop;
    
        return DB::table('cliente_escala')
            ->where('id_cliente_escala', $id)
            ->update([
                'coletor'   => $coletor,
                'desktop'   => $desktop,
                'licenca'   => $total,
                'updated_at'=> now()
            ]) > 0;
    }

}
