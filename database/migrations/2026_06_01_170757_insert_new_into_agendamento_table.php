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
                'name'  => 'Libera acesso ao GDT plantão 24',
                'command' => 'plantao:suporte',
                'description' => 'Sincroniza acessos do pessoal do 24 servicedesk/gdt.',
                'active' => 1
            ],
        ]);
    }

    public function down()
    {
        DB::table('agendamentos')
            ->whereIn('command', ['plantao:suporte'])
            ->delete();
    }
};
