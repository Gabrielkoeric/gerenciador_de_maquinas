<?php

namespace App\Repositories\VmServico;

use Illuminate\Support\Facades\DB;

class VmServicoRepository
{
    public function getByClienteAndServico(int $idClienteEscala, int $idServico)
    {
        return DB::table('servico_vm')
            ->where('id_cliente_escala', $idClienteEscala)
            ->where('id_servico', $idServico)
            ->first();
    }

    public function create(array $data): bool
    {
        return DB::table('servico_vm')->insert($data);
    }

    public function updateVmByClienteAndServico(
        int $idClienteEscala,
        int $idServico,
        int $idVm
    ): int {
        return DB::table('servico_vm')
            ->where('id_cliente_escala', $idClienteEscala)
            ->where('id_servico', $idServico)
            ->update([
                'id_vm' => $idVm,
            ]);
    }
}
