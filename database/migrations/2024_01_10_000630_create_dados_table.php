<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('dados', function (Blueprint $table) {
            $table->id('id_dados')->autoIncrement();
            $table->string('sigla');
            $table->string('nome');
            $table->timestamps();
        });

        DB::table('dados')->insert([
            [
                'id_dados' => 1,
                'sigla' => 'KB',
                'nome' => 'kilobyte',
            ],
            [
                'id_dados' => 2,
                'sigla' => 'Mb',
                'nome' => 'megabyte',
            ],
            [
                'id_dados' => 3,
                'sigla' => 'GB',
                'nome' => 'gigabyte',
            ],
            [
                'id_dados' => 4,
                'sigla' => 'TB',
                'nome' => 'terabyte',
            ],
            [
                'id_dados' => 5,
                'sigla' => 'PB',
                'nome' => 'petabyte',
            ],
            [
                'id_dados' => 6,
                'sigla' => 'EB',
                'nome' => 'exabyte',
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
        Schema::dropIfExists('dados');
    }
};
