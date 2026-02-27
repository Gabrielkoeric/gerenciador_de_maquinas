<?php

namespace App\Repositories\UsuarioVm;

use Illuminate\Support\Facades\DB;

class UsuarioVmRepository
{
    public function buscarPorVm(int $idVm)
    {
        return DB::table('usuario_vm')
            ->where('id_vm', $idVm)
            ->first();
    }
}