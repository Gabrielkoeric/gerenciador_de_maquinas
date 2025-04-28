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
        Schema::create('config_geral', function (Blueprint $table) {
            $table->id('id_config_geral');
            $table->string('nomeConfig')->nullable();
            $table->string('valorConfig')->nullable();
            $table->timestamps();
        });

        DB::table('config_geral')->insert([
            [
                'id_config_geral' => 1,
                'nomeConfig' => 'metodo_autenticacao',
                'valorConfig' => 'login_local', #login_local pra rotina local e google pra integração com o google.
            ],
            [
                'id_config_geral' => 2,
                'nomeConfig' => 'email_administrador',
                'valorConfig' => 'administrador@gmail.com',
            ],
            [
                'id_config_geral' => 3,
                'nomeConfig' => 'url_api_cliente',
                'valorConfig' => 'url',
            ],
            [
                'id_config_geral' => 4,
                'nomeConfig' => 'modo_manutencao',
                'valorConfig' => '0',
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
        Schema::dropIfExists('config_geral');
    }
};
