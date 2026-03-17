<?php

namespace App\Repositories\UsuariosLogados;

use Illuminate\Support\Facades\DB;

class UsuariosLogadosRepository
{
    public function countLogados()
    {
/*
        $ultimoHorario = DB::table('horario_auditoria')
            ->select('id_horario_auditoria', 'data_hora')
            ->orderByDesc('id_horario_auditoria')
            ->limit(1);
*/

        $ultimosHorarios = DB::table('horario_auditoria')
            ->select('id_horario_auditoria', 'data_hora')
            ->orderByDesc('id_horario_auditoria')
            ->limit(2)
            ->get();

        $ultimo = $ultimosHorarios[0];
        $anterior = $ultimosHorarios[1] ?? null;
/*
        return DB::table('cliente_escala as c')
            ->crossJoinSub($ultimoHorario, 'h')
            ->leftJoin('auditoria_secao as a', function ($join) {
                $join->on('a.id_cliente_escala', '=', 'c.id_cliente_escala')
                     ->on('a.id_horario_auditoria', '=', 'h.id_horario_auditoria');
            })
            ->where('c.ativo', 1)
            ->select(
                'c.nome as cliente_nome',
                'h.data_hora as data',
                DB::raw('COALESCE(a.quantidade,0) as quantidade')
            )
            ->orderBy('quantidade', 'DESC')
            ->get();
*/
        $query = DB::table('cliente_escala as c')
            ->leftJoin('auditoria_secao as a1', function ($join) use ($ultimo) {
                $join->on('a1.id_cliente_escala', '=', 'c.id_cliente_escala')
                     ->where('a1.id_horario_auditoria', '=', $ultimo->id_horario_auditoria);
            })
            ->leftJoin('auditoria_secao as a2', function ($join) use ($anterior) {
                if ($anterior) {
                    $join->on('a2.id_cliente_escala', '=', 'c.id_cliente_escala')
                         ->where('a2.id_horario_auditoria', '=', $anterior->id_horario_auditoria);
                }
            })
            ->where('c.ativo', 1)
            ->select(
                'c.nome as cliente_nome',
                DB::raw("'" . $ultimo->data_hora . "' as data"),
                DB::raw('COALESCE(a1.quantidade,0) as quantidade_atual'),
                DB::raw('COALESCE(a2.quantidade,0) as quantidade_anterior'),
                DB::raw('COALESCE(a1.quantidade,0) - COALESCE(a2.quantidade,0) as diferenca')
            )
            ->orderBy('quantidade_atual', 'DESC')
            ->get();
        
        return $query;
    }
}