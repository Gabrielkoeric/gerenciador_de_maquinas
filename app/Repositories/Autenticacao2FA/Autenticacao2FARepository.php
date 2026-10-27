<?php

namespace App\Repositories\Autenticacao2FA;

use Illuminate\Support\Facades\DB;

class Autenticacao2FARepository
{
    protected $tabela = 'autenticacao_2fa';

    public function criar(array $dados)
    {
        // Invalida desafios anteriores ainda pendentes deste usuário.
        DB::table($this->tabela)
            ->where('id_usuario', $dados['id_usuario'])
            ->whereNull('validado_em')
            ->update([
                'expira_em' => now(),
                'updated_at' => now(),
            ]);

        $id = DB::table($this->tabela)->insertGetId(
            array_merge($dados, [
                'created_at' => now(),
                'updated_at' => now(),
            ]),
            'id_autenticacao_2fa'
        );

        return DB::table($this->tabela)
            ->where('id_autenticacao_2fa', $id)
            ->first();
    }

    public function buscarPorId($id)
    {
        return DB::table($this->tabela)
            ->where('id_autenticacao_2fa', $id)
            ->first();
    }

    public function incrementarTentativas($id)
    {
        return DB::table($this->tabela)
            ->where('id_autenticacao_2fa', $id)
            ->increment('tentativas', 1, [
                'updated_at' => now(),
            ]);
    }

    public function marcarComoValidado($id)
    {
        return DB::table($this->tabela)
            ->where('id_autenticacao_2fa', $id)
            ->whereNull('validado_em')
            ->update([
                'validado_em' => now(),
                'updated_at' => now(),
            ]);
    }

    public function invalidar($id)
    {
        return DB::table($this->tabela)
            ->where('id_autenticacao_2fa', $id)
            ->update([
                'expira_em' => now(),
                'updated_at' => now(),
            ]);
    }
}

