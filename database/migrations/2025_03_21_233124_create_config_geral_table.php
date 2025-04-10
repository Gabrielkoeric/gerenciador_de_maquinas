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
            $table->string('nome_config')->nullable();
            $table->string('valor_config')->nullable();
            $table->timestamps();
        });

        DB::table('config_geral')->insert([
            [
                'id_config_geral' => 1,
                'nome_config' => 'metodo_autenticacao',
                'valor_config' => 'login_local', #login_local pra rotina local e google pra integração com o google.
            ],
            [
                'id_config_geral' => 2,
                'nome_config' => 'email_administrador',
                'valor_config' => 'administrador@gmail.com',
            ],
            [
                'id_config_geral' => 3,
                'nome_config' => 'url_api_cliente',
                'valor_config' => 'url',
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
