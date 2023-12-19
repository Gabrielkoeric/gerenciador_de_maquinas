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
                'nome' => 'Logs de Acesso',
                'nome_tela' => 'access_logs',
                'imagem_tela' => 'storage/home/log_access.png',
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
