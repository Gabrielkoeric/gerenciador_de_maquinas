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
        Schema::create('servico_vm', function (Blueprint $table) {
            $table->id('id_servico_vm');
            $table->string('porta');
            $table->string('tipo');
            $table->timestamps();

            $table->unsignedBigInteger('id_vm');
            $table->unsignedBigInteger('id_servico');
            $table->unsignedBigInteger('id_cliente_escala');

            $table->foreign('id_vm')->references('id_vm')->on('vm');
            $table->foreign('id_servico')->references('id_servico')->on('servico');
            $table->foreign('id_cliente_escala')->references('id_cliente_escala')->on('cliente_escala');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servico_vm');
    }
};
