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
        Schema::create('servidor_fisico', function (Blueprint $table) {
            $table->id('id_servidor_fisico');
            $table->string('nome');
            $table->string('dns')->nullable();
            $table->string('ip_wan')->nullable();
            $table->string('ip_lan');
            $table->string('porta');
            $table->string('dominio')->nullable();
            $table->string('tipo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servidor_fisico');
    }
};
