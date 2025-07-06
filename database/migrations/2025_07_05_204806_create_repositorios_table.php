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
        Schema::create('repositorios', function (Blueprint $table) {
            $table->id('id_repositorios');
            $table->string('nome')->nullable(); 
            $table->string('tipo')->nullable();
            $table->integer('prioridade')->nulable(); 
            $table->string('rclone')->nullable(); 
            $table->string('tipo_copia')->default('copy');
            $table->string('origem');
            $table->string('destino');
            $table->string('log_dir')->nullable(); 
            $table->text('tags')->nullable();
            $table->boolean('ativo')->default(true);

            $table->unsignedBigInteger('id_cliente_escala');
            $table->foreign('id_cliente_escala')->references('id_cliente_escala')->on('cliente_escala');

            $table->unsignedBigInteger('id_vm');
            $table->foreign('id_vm')->references('id_vm')->on('vm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repositorios');
    }
};
