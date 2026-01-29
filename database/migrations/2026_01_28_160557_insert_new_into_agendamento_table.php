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
        DB::table('agendamentos')->insert([
            [
                'name'  => 'Sync Info Clientes',
                'command' => 'sync:versao',
                'description' => 'Agendamento para sincronizar as informações dos clientes versão, data de atualização e licenciado até.',
                'active' => 1
            ],
        ]);
    }

    public function down()
    {
        DB::table('agendamentos')
            ->whereIn('command', ['sync:versao'])
            ->delete();
    }
};
