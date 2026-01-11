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
        Schema::create('logs_sql', function (Blueprint $table) {
            $table->bigIncrements('id_log_sql');
            $table->unsignedBigInteger('id_rotina');
            $table->unsignedBigInteger('id_acao');
            $table->unsignedBigInteger('id')->nullable();
            $table->text('sql');
            $table->json('bindings')->nullable();
            $table->longText('sql_full')->nullable();
            $table->decimal('tempo_ms', 8, 2)->index();
            $table->string('connection', 20);
            $table->string('database', 50);
            $table->string('url', 255);
            $table->string('rota', 100)->nullable()->index();
            $table->string('metodo_http', 10);
            $table->string('ip', 45)->nullable();
            $table->string('controller', 150)->nullable();
            $table->timestamp('executado_em');
            // Foreign Keys (opcional mas recomendado)
            $table->foreign('id_rotina')->references('id_rotina')->on('rotinas');
            $table->foreign('id_acao')->references('id_acao')->on('acoes');
            $table->foreign('id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs_sql');
    }
};
