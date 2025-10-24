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
        Schema::create('horarios_agendamentos', function (Blueprint $table) {
            $table->id('id_horarios_agendamentos');
            $table->string('expression')->nullable(); // expressão cron gerada (ex: "0 19 * * *")
            $table->string('type')->nullable();       // tipo legível: 'diario','semanal','range','every_x','monthly','custom', etc.
            $table->json('meta')->nullable();         // metadados do front (ex: {"dias":["mon","fri"],"hora":"20:00","intervalo":30})
            $table->boolean('active')->default(true); // ativo/inativo para esse horário específico
            $table->timestamps();

            $table->unsignedBigInteger('id_agendamentos');
            $table->foreign('id_agendamentos')->references('id_agendamentos')->on('agendamentos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horarios_agendamentos');
    }
};
