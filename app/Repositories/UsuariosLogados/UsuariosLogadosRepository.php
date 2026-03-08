<?php

namespace App\Repositories\UsuariosLogados;

use Illuminate\Support\Facades\DB;

class UsuariosLogadosRepository
{
    public function countLogados()
    {
        $ultimoHorario = DB::table('horario_auditoria')
            ->select('id_horario_auditoria', 'data_hora')
            ->orderByDesc('id_horario_auditoria')
            ->limit(1);

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
            ->orderBy('c.nome')
            ->get();
    }
}