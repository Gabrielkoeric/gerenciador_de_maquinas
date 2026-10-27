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

    public function buscarPorEmail(string $email)
    {
        return DB::table('usuarios')
            ->where('email', $email)
            ->first();
    }

    public function incrementarTentativasLogin(int $idUsuario): int
    {
        DB::table('usuarios')
            ->where('id', $idUsuario)
            ->increment('tentativas_login');

        return (int) DB::table('usuarios')
            ->where('id', $idUsuario)
            ->value('tentativas_login');
    }

    public function bloquearSeAtingirLimite(
        int $idUsuario,
        int $limite = 3
    ): bool {
        return DB::table('usuarios')
            ->where('id', $idUsuario)
            ->where('tentativas_login', '>=', $limite)
            ->update(['ativo' => false]) > 0;
    }

    public function zerarTentativasLogin(int $idUsuario): void
    {
        DB::table('usuarios')
            ->where('id', $idUsuario)
            ->update(['tentativas_login' => 0]);
    }

    public function atualizarTentativasEStatus(
        int $idUsuario,
        int $tentativas,
        bool $ativo
    ): void {
        DB::table('usuarios')
            ->where('id', $idUsuario)
            ->update([
                'tentativas_login' => $tentativas,
                'ativo' => $ativo,
            ]);
    }
}
