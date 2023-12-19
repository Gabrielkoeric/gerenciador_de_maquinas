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
        Schema::create('produtos_disponiveis_retiradas', function (Blueprint $table) {
            $table->id('id_produtos_disponiveis_retiradas')->autoIncrement();
            $table->integer('quantidade');
            $table->unsignedBigInteger('id_produtos_disponiveis');
            $table->unsignedBigInteger('id_retirada');
            $table->timestamps();

            $table->foreign('id_produtos_disponiveis')->references('id_produtos_disponiveis')->on('produtos_disponiveis');
            $table->foreign('id_retirada')->references('id_retirada')->on('retiradas');

        });
        DB::unprepared('
            CREATE TRIGGER after_insert_produtos_disponiveis_retiradas
            AFTER INSERT ON produtos_disponiveis_retiradas
            FOR EACH ROW
            BEGIN
                DECLARE quantidade_retirada INT;

                -- Obter a quantidade retirada
                SELECT NEW.quantidade INTO quantidade_retirada;

                -- Subtrair a quantidade da tabela produtos_disponiveis
                UPDATE produtos_disponiveis
                SET quantidade = quantidade - quantidade_retirada
                WHERE id_produtos_disponiveis = NEW.id_produtos_disponiveis;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos_disponiveis_retiradas');
    }
};
