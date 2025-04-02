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
        Schema::create('vm', function (Blueprint $table) {
            $table->id('id_vm');
            $table->string('nome');
            $table->string('dns')->nullable();
            $table->string('ipwan')->nullable();
            $table->string('iplan');
            $table->string('porta');
            $table->string('dominio')->nullable();
            $table->string('tipo');
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
        Schema::dropIfExists('vm');
    }
};
