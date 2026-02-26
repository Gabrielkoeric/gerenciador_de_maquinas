<?php

namespace App\Repositories\Repositorio;

use Illuminate\Support\Facades\DB;

class RepositorioRepository
{
    public function getRepositoriosDiariosArquivo()
    {
        return DB::table('repositorios')
            ->where('diario', 1)
            ->where('tipo', 'arquivo')
            ->orderBy('prioridade', 'asc')
            ->get();
    }
}