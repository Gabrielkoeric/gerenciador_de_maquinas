<?php

namespace App\Repositories\Servicos;

use Illuminate\Support\Facades\DB;

class ServicosRepository
{
    public function getIdByNome(string $nome): ?int
    {
        return DB::table('servico')
            ->where('nome', $nome)
            ->value('id_servico');
    }
}