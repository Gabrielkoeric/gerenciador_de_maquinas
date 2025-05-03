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
        DB::table('home')->insert([
            [
                'nome' => 'Dominios',
                'nome_tela' => 'dominios',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('perfil_permissao')->insert([
            ['id_home' => 30, 'id_perfil' => 2],
            ['id_home' => 30, 'id_perfil' => 3],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('home', function (Blueprint $table) {
            //
        });
    }
};
