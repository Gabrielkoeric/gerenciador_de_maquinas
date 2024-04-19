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
        Schema::create('perfil_permissao', function (Blueprint $table) {
            $table->id('id_perfil_permissao');

            $table->unsignedBigInteger('id_home');
            $table->unsignedBigInteger('id_perfil');

            $table->foreign('id_home')->references('id_home')->on('home');
            $table->foreign('id_perfil')->references('id_perfil')->on('perfil');
        });

        $dadosPadrao = [
            //adm
            ['id_home' => 1, 'id_perfil' => 2],
            ['id_home' => 2, 'id_perfil' => 2],
            ['id_home' => 3, 'id_perfil' => 2],
            ['id_home' => 4, 'id_perfil' => 2],
            ['id_home' => 5, 'id_perfil' => 2],
            ['id_home' => 6, 'id_perfil' => 2],
            ['id_home' => 7, 'id_perfil' => 2],
            ['id_home' => 8, 'id_perfil' => 2],
            ['id_home' => 9, 'id_perfil' => 2],
            ['id_home' => 10, 'id_perfil' => 2],
            ['id_home' => 11, 'id_perfil' => 2],
        ];

        DB::table('perfil_permissao')->insert($dadosPadrao);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfil_permissao');
    }
};
