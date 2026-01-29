<?php

namespace App\Repositories\Versoes;

use Illuminate\Support\Facades\DB;

class VersoesRepository
{
    public function listarNomeVersoes()
    {
        return DB::table('versoes')
            ->orderBy('nome')
            ->pluck('nome');
    }

}
