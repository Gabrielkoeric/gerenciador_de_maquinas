<?php

namespace App\Repositories\WebService;

use Illuminate\Support\Facades\DB;
use App\Services\SqlMonitorService;

class WebServiceRepository
{
    public function listarDadosWs()
    {
        return DB::table('cliente_escala as c')
            ->select([
                'c.id_cliente_escala',
                'c.nome as cliente_nome',
                'c.apelido',
                'c.config_ws',
                'vm.nome as vm_nome',
                'sv.porta as porta_ws',
                'cfg_ws.valorConfig as rota_padrao_ws',
                'cfg_cloud.valorConfig as rota_padrao_ws_cloud',
            ])
            ->join('servico_vm as sv', function ($join) {
                $join->on('sv.id_cliente_escala', '=', 'c.id_cliente_escala')
                     ->where('sv.id_servico', 8)
                     ->whereRaw("sv.nome = CONCAT('escala_ws_', c.apelido)");
            })
            ->join('vm', 'vm.id_vm', '=', 'sv.id_vm')
            ->leftJoin('config_geral as cfg_ws', function ($join) {
                $join->where('cfg_ws.nomeConfig', 'rota_padrao_ws');
            })
            ->leftJoin('config_geral as cfg_cloud', function ($join) {
                $join->where('cfg_cloud.nomeConfig', 'rota_padrao_ws_cloud');
            })
            ->where('c.ativo', 1)
            ->orderBy('c.nome')
            ->get();
    }
}
