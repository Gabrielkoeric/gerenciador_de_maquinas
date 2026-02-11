<?php

namespace App\Repositories\Vm;

use Illuminate\Support\Facades\DB;

class VmRepository
{
    public function listarSgbd()
    {
        return DB::table('vm')
            ->join('ip_lan', 'ip_lan.id_ip_lan', '=', 'vm.id_ip_lan')
            ->select(
                'vm.id_vm',
                'vm.nome',
                'vm.so',
                'ip_lan.ip as ip_lan'
            )
            ->where('vm.tipo', 'sgbd')
            ->orderBy('vm.nome', 'asc') // ou 'desc'
            ->get();
    }

    public function getVms()
    {
        return DB::table('vm')
            ->select('nome')
            ->orderBy('nome')
            ->get();
    }
}