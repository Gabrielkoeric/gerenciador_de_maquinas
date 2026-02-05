<?php

namespace App\Repositories\Acessos;

use Illuminate\Support\Facades\DB;

class AcessosRepository
{
    public function getAcesso(int $id_acesso)
    {
        return DB::table('acessos')
            ->select('usuario', 'senha')
            ->where('id_acesso', $id_acesso)
            ->first();
    }
}