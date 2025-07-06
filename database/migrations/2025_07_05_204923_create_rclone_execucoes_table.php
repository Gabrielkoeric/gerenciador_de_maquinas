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
        Schema::create('rclone_execucoes', function (Blueprint $table) {
            $table->id('id_execucao');
            $table->timestamp('disparo')->nullable();
            $table->timestamp('inicio')->nullable();
            $table->timestamp('fim')->nullable();
            $table->string('status')->default('pendente');

            $table->integer('qtd_arquivos_transferidos')->nullable();
            $table->integer('qtd_arquivos_check')->nullable();
            $table->bigInteger('bytes_transferidos')->nullable();

            $table->text('log_path')->nullable();
            $table->text('erro')->nullable();

            $table->unsignedBigInteger('id_repositorio');
            $table->foreign('id_repositorio')->references('id_repositorios')->on('repositorios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rclone_execucoes');
    }
};
