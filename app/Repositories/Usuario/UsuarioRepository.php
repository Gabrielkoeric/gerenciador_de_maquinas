<?php

namespace App\Repositories\Usuario;

use Illuminate\Support\Facades\DB;

class UsuarioRepository
{
    public function listarCompleto()
    {
        return DB::table('usuarios')
            ->select('id', 'email')
            ->orderBy('email')
            ->get();
    }
}
