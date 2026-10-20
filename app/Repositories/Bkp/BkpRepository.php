<?php

namespace App\Repositories\Bkp;

use Illuminate\Support\Facades\DB;

class BkpRepository
{
    public function inserirSeNaoExistir(array $dados): void
    {
        DB::table('bkp')->insertOrIgnore($dados);
    }

    public function buscarBackupsSemTamanho()
    {
        return DB::table('bkp')
            ->join(
                'cliente_escala',
                'bkp.id_cliente_escala',
                '=',
                'cliente_escala.id_cliente_escala'
            )
            ->where('bkp.tamanho_bytes', 0)
            ->orderBy('bkp.id_cliente_escala')
            ->orderBy('bkp.id_bkp')
            ->select(
                'bkp.id_bkp',
                'bkp.objeto',
                'bkp.id_cliente_escala',
                'cliente_escala.apelido'
            )
            ->get();
    }
    
    public function atualizarTamanho(int $idBkp, int $tamanho): bool
    {
        return DB::table('bkp')
            ->where('id_bkp', $idBkp)
            ->where('tamanho_bytes', 0)
            ->update(['tamanho_bytes' => $tamanho]) > 0;
    }
}