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
        Schema::create('logs_execucoes', function (Blueprint $table) {
            $table->id('id_logs_execucoes');
            $table->string('acao');                       // start, stop, restart, status...
            $table->string('playbook')->nullable();       // Nome do playbook executado
            $table->text('comando')->nullable();          // Comando executado
            $table->longText('saida')->nullable();        // Saída do shell_exec
            $table->enum('status', ['sucesso', 'falha'])->default('sucesso'); // Status da execução
            $table->text('erro')->nullable();             // Mensagem de erro, se houver
            $table->timestamp('executado_em')->useCurrent(); // Data/hora da execução
            $table->timestamps(); 

            $table->unsignedBigInteger('id');          // Referência à tabela usuarios
            $table->unsignedBigInteger('id_servico_vm');

            $table->foreign('id')->references('id')->on('usuarios');
            $table->foreign('id_servico_vm')->references('id_servico_vm')->on('servico_vm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs_execucoes');
    }
};
