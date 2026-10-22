<?php

namespace App\Repositories\PermissaoBkpCliente;

use Illuminate\Support\Facades\DB;

class PermissaoBkpClienteRepository
{
    public function listarCompleto()
    {
        return DB::table('permissao_bkp_cliente as permissao')
            ->join(
                'cliente_escala as cliente',
                'permissao.id_cliente_escala',
                '=',
                'cliente.id_cliente_escala'
            )
            ->join(
                'usuarios as usuario',
                'permissao.id_usuario',
                '=',
                'usuario.id'
            )
            ->join(
                'usuarios as liberador',
                'permissao.id_usuario_liberacao',
                '=',
                'liberador.id'
            )
            ->select(
                'permissao.*',
                'cliente.apelido',
                'usuario.nome_completo as nome_usuario',
                'usuario.email as email_usuario',
                'liberador.nome_completo as nome_liberador'
            )
            ->orderByDesc('permissao.data_liberacao')
            ->get();
    }

    public function create(array $dados)
    {
        return DB::table('permissao_bkp_cliente')->insert($dados);
    }
}