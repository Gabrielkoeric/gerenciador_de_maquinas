<?php

namespace App\Repositories\Bkp;

use Illuminate\Support\Facades\DB;

class BkpRepository
{
    public function inserirSeNaoExistir(array $dados): void
    {
        DB::table('bkp')->insertOrIgnore($dados);
    }
}