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
                'name'  => 'Coleta Info VMWare',
                'command' => 'list:vmware',
                'description' => 'Coleta e compara as VMs do VMWare com as VM cadastradas no Gerenciador.',
                'active' => 1
            ],
        ]);
    }

    public function down()
    {
        DB::table('agendamentos')
            ->whereIn('command', ['list:vmware'])
            ->delete();
    }
};
