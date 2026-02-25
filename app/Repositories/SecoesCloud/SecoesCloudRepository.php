<?php

namespace App\Repositories\SecoesCloud;

use Illuminate\Support\Facades\DB;

class SecoesCloudRepository
{
    public function countUsuariosSecaoCloud(int $idCliente): array
    {
        $result = DB::table('secao_cloud')
            ->selectRaw("
                SUM(CASE WHEN coletor = 1 THEN 1 ELSE 0 END) as coletor,
                SUM(CASE WHEN coletor = 0 THEN 1 ELSE 0 END) as desktop
            ")
            ->where('id_cliente_escala', $idCliente)
            ->first();

        $coletor = (int) $result->coletor;
        $desktop = (int) $result->desktop;

        return [
            'coletor' => $coletor,
            'desktop' => $desktop,
        ];
    }

    public function getUltimoUsuario(int $idCliente, int $coletor): ?object
    {
        return DB::table('secao_cloud')
            ->where('id_cliente_escala', $idCliente)
            ->where('coletor', $coletor)
            ->orderByDesc('usuario') // funciona por causa do %02d
            ->first();
    }

    public function deleteById(int $id): void
    {
        DB::table('secao_cloud')
            ->where('id_secao_cloud', $id)
            ->delete();
    }
}