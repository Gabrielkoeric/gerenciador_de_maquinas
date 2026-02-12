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

    public function getByTipo(string $tipo)
    {
        return DB::table('vm')
            ->leftJoin('servico_vm', 'vm.id_vm', '=', 'servico_vm.id_vm')
            ->select(
                'vm.id_vm',
                'vm.nome',
                DB::raw('COUNT(servico_vm.id_servico_vm) as total_servicos')
            )
            ->where('vm.tipo', $tipo)
            ->groupBy('vm.id_vm', 'vm.nome')
            ->get();
    }

    public function getById(int $idVm)
    {
        return DB::table('vm')
            ->select(
                'vm.*',
                'ip_lan.ip as ip_lan_vm',
                'dominio.nome as dominio_nome',
                'dominio.usuario as dominio_usuario',
                'dominio.senha as dominio_senha',
                'usuario_vm.usuario as usuario_local',
                'usuario_vm.senha as senha_local'
            )
            ->leftJoin('ip_lan', 'vm.id_ip_lan', '=', 'ip_lan.id_ip_lan')
            ->leftJoin('dominio', 'vm.id_dominio', '=', 'dominio.id_dominio')
            ->leftJoin('usuario_vm', function ($join) {
                $join->on('vm.id_vm', '=', 'usuario_vm.id_vm')
                     ->where('usuario_vm.principal', 1);
            })
            ->where('vm.id_vm', $idVm)
            ->first();
    }
}