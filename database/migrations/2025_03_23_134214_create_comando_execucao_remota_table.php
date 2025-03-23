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
        Schema::create('comando_execucao_remota', function (Blueprint $table) {
            $table->id('id_comando_execucao_remota');
            $table->string('tipo');
            $table->string('acao');
            $table->string('comando');
            $table->timestamps();
        });

        DB::table('comando_execucao_remota')->insert([
            [
                'id_comando_execucao_remota' => 1,
                'tipo' => 'ssh',
                'acao' => 'start',
                'comando' => 'systemctl start {servico}',
            ],
            [
                'id_comando_execucao_remota' => 2,
                'tipo' => 'ssh',
                'acao' => 'stop',
                'comando' => 'systemctl stop {servico}',
            ],
            [
                'id_comando_execucao_remota' => 3,
                'tipo' => 'ssh',
                'acao' => 'restart',
                'comando' => 'systemctl restart {servico}',
            ],
            [
                'id_comando_execucao_remota' => 4,
                'tipo' => 'ssh',
                'acao' => 'status',
                'comando' => 'systemctl status {servico} --no-pager',
            ],
            [
                'id_comando_execucao_remota' => 5,
                'tipo' => 'rdp',
                'acao' => 'start',
                'comando' => 'Start-Service -Name {servico}',
            ],
            [
                'id_comando_execucao_remota' => 6,
                'tipo' => 'rdp',
                'acao' => 'stop',
                'comando' => 'Stop-Service -Name {servico}',
            ],
            [
                'id_comando_execucao_remota' => 7,
                'tipo' => 'rdp',
                'acao' => 'restart',
                'comando' => 'Restart-Service -Name {servico}',
            ],
            [
                'id_comando_execucao_remota' => 8,
                'tipo' => 'rdp',
                'acao' => 'status',
                'comando' => 'Get-Service -Name {servico} | Select-Object Status',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comando_execucao_remota');
    }
};
