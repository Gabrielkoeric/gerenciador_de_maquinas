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
                'name'  => 'Sync Banco de Dados Clientes',
                'command' => 'sync:db',
                'description' => 'Agendamento para sincronizar as informações de banco de dados de cada cliente.',
                'active' => 1
            ],
        ]);
    }

    public function down()
    {
        DB::table('agendamentos')
            ->whereIn('command', ['sync:db'])
            ->delete();
    }
};
