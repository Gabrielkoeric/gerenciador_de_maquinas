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
        Schema::create('usuario_servidor_fisico', function (Blueprint $table) {
            $table->id('id_usuario_servidor_fisico');
            $table->string('usuario');
            $table->string('senha');
            $table->timestamps();

            $table->unsignedBigInteger('id_servidor_fisico');
            $table->foreign('id_servidor_fisico')->references('id_servidor_fisico')->on('servidor_fisico');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_servidor_fisico');
    }
};
