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
        Schema::create('compras_estoque', function (Blueprint $table) {
            $table->id('id_compra_estoque');
            $table->unsignedBigInteger('id_compra');
            $table->unsignedBigInteger('id_produto_estoque');
            $table->integer('quantidade_compra');
            $table->integer('quantidade_restante');
            $table->timestamps();

            $table->foreign('id_compra')->references('id_compra')->on('compras');
            $table->foreign('id_produto_estoque')->references('id_produto_estoque')->on('estoques');

        });
        DB::unprepared('
        CREATE TRIGGER subtrair_estoque_after_insert
        AFTER INSERT ON compras_estoque FOR EACH ROW
        BEGIN
            UPDATE estoques
            SET quantidade_atual = quantidade_atual - NEW.quantidade_compra
            WHERE id_produto_estoque = NEW.id_produto_estoque;
        END
    ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras_estoque');
    }
};
