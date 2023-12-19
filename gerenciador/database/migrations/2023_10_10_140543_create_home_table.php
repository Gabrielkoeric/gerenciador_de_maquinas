<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home', function (Blueprint $table) {
            $table->id('id_home');
            $table->string('nome');
            $table->string('nome_tela');
            $table->string('imagem_tela');
            $table->boolean('permite_home');
            $table->timestamps();
        });

        $dadosPadraoHome = [
            [
                'nome' => 'Usuarios',
                'nome_tela' => 'usuario',
                'imagem_tela' => 'storage/home/usuarios.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Estoque',
                'nome_tela' => 'estoque',
                'imagem_tela' => 'storage/home/estoque.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Compra',
                'nome_tela' => 'compra',
                'imagem_tela' => 'storage/home/compraProdutos.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Vendas',
                'nome_tela' => 'vendas',
                'imagem_tela' => 'storage/home/vendas.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Pedidos',
                'nome_tela' => 'pedidos',
                'imagem_tela' => 'storage/home/pedidos.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Produtos',
                'nome_tela' => 'produtos',
                'imagem_tela' => 'storage/home/produtos.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Ingressos',
                'nome_tela' => 'ingressos',
                'imagem_tela' => 'storage/home/ingressos.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Lote',
                'nome_tela' => 'lote',
                'imagem_tela' => 'storage/home/lote.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Compra Ingressos',
                'nome_tela' => 'compra_ingressos',
                'imagem_tela' => 'storage/home/bilheteria.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Nomeação de Ingressos',
                'nome_tela' => 'nomeacao',
                'imagem_tela' => 'storage/home/nomeacao.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Check',
                'nome_tela' => 'check',
                'imagem_tela' => 'storage/home/check-in_out.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Resultados',
                'nome_tela' => 'resultados',
                'imagem_tela' => 'storage/home/resultados.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Relatório',
                'nome_tela' => 'resultadosingresso',
                'imagem_tela' => 'relatorio',
                'permite_home' => 0,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Relatório',
                'nome_tela' => 'resultadosproduto',
                'imagem_tela' => 'relatorio',
                'permite_home' => 0,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Relatório',
                'nome_tela' => 'relatorioresultados',
                'imagem_tela' => 'relatorio',
                'permite_home' => 0,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Logs de Acesso',
                'nome_tela' => 'access_logs',
                'imagem_tela' => 'storage/home/log_access.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Logs Check-In, Check-Out',
                'nome_tela' => 'logs_check',
                'imagem_tela' => 'storage/home/log_check.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Perfis de Usuarios',
                'nome_tela' => 'perfis_usuarios',
                'imagem_tela' => 'storage/home/perfis_usuarios.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Entrega de Produtos',
                'nome_tela' => 'produto_entrega',
                'imagem_tela' => 'n/t',
                'permite_home' => 0,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Liberação Check',
                'nome_tela' => 'check_liberacao',
                'imagem_tela' => 'n/t',
                'permite_home' => 0,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Gera Compra',
                'nome_tela' => 'gera',
                'imagem_tela' => 'storage/home/vendas.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
        ];

        DB::table('home')->insert($dadosPadraoHome);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home');
    }
};
