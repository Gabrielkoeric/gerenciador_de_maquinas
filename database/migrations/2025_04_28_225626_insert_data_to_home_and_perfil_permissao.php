<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class InsertDataToHomeAndPerfilPermissao extends Migration
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
                'nome' => 'Seção Cloud',
                'nome_tela' => 'secao_cloud',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Outros dados a serem inseridos na tabela 'home'
        ]);

        // Inserir dados na tabela 'perfil_permissao'
        DB::table('perfil_permissao')->insert([
            [
                'id_home' => 21, // ID da entrada na tabela 'home' que você quer associar
                'id_perfil' => 2, // ID do perfil na tabela 'perfil'
            ],
            [
                'id_home' => 21, // ID da entrada na tabela 'home'
                'id_perfil' => 3, // ID do perfil na tabela 'perfil'
            ],
            // Outros dados a serem inseridos na tabela 'perfil_permissao'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Você pode adicionar um rollback caso precise
        DB::table('perfil_permissao')->whereIn('id_home', [21])->delete();
        DB::table('home')->whereIn('id_home', [21])->delete();
    }
}
