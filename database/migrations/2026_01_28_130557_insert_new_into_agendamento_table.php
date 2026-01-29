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
                'name'  => 'Sync Codigos Web',
                'command' => 'sync:web',
                'description' => 'Agendamento para a realização da sincronização dos códigos web com o gerenciador de máquinas',
                'active' => 1
            ],
        ]);
    }

    public function down()
    {
        DB::table('agendamentos')
            ->whereIn('command', ['sync:web'])
            ->delete();
    }
};
