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
        Schema::create('compras', function (Blueprint $table) {
            $table->id('id_compra');
            $table->unsignedBigInteger('id');
            $table->decimal('valor', 10, 2);
            $table->string('status');
            $table->string('hash');
            $table->string('link_pagamento')->nullable();
            $table->boolean('entregue')->nullable();
            $table->timestamps();

            $table->foreign('id')->references('id')->on('usuarios');
        });/*
        DB::unprepared('
            CREATE TRIGGER tr_compra_approved AFTER UPDATE ON compras FOR EACH ROW
            BEGIN
                IF NEW.status = "approved" AND OLD.status <> "approved" THEN
                    INSERT INTO produtos_disponiveis (id, id_produto_estoque, quantidade)
                    SELECT NEW.id, ce.id_produto_estoque, ce.quantidade_compra
                    FROM compras_estoque ce
                    WHERE ce.id_compra = NEW.id_compra
                    ON DUPLICATE KEY UPDATE quantidade = quantidade + VALUES(quantidade);
                END IF;
            END;
        ');*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras');
    }
};
