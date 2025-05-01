<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        // Inserir dados na tabela 'home'
        DB::table('home')->insert([
            [
                'nome' => 'Redes',
                'nome_tela' => 'redes',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'IP',
                'nome_tela' => 'ip_lan',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Documentação',
                'nome_tela' => 'documentacao',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Deploy',
                'nome_tela' => 'deploy',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Status Serviços',
                'nome_tela' => 'status_servicos',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Logs Eventos',
                'nome_tela' => 'logs_eventos',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Acessos Gerais',
                'nome_tela' => 'acessos_gerais',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Logs Status VM',
                'nome_tela' => 'logs_eventos',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Outros dados a serem inseridos na tabela 'home'
        ]);

        // Inserir dados na tabela 'perfil_permissao'
        DB::table('perfil_permissao')->insert([
            ['id_home' => 22, 'id_perfil' => 2],
            ['id_home' => 22, 'id_perfil' => 3],
            ['id_home' => 23, 'id_perfil' => 2],
            ['id_home' => 23, 'id_perfil' => 3],
            ['id_home' => 24, 'id_perfil' => 2],
            ['id_home' => 24, 'id_perfil' => 3],
            ['id_home' => 25, 'id_perfil' => 2],
            ['id_home' => 25, 'id_perfil' => 3],
            ['id_home' => 26, 'id_perfil' => 2],
            ['id_home' => 26, 'id_perfil' => 3],
            ['id_home' => 27, 'id_perfil' => 2],
            ['id_home' => 27, 'id_perfil' => 3],
            ['id_home' => 28, 'id_perfil' => 2],
            ['id_home' => 28, 'id_perfil' => 3],
            ['id_home' => 29, 'id_perfil' => 2],
            ['id_home' => 29, 'id_perfil' => 3],
        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home_and_perfil_permissao2', function (Blueprint $table) {
            //
        });
    }
};
