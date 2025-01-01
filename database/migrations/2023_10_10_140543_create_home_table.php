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
        Schema::create('home', function (Blueprint $table) {
            $table->id('id_home');
            $table->string('nome');
            $table->string('nome_tela');
            $table->string('imagem_tela');
            $table->boolean('permite_home');
            $table->timestamps();
        });

        $dadosPadraoHome = [
            [
                'nome' => 'Servidores Fisicos',
                'nome_tela' => 'server',
                'imagem_tela' => 'storage/home/servidor.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'CPU',
                'nome_tela' => 'cpu',
                'imagem_tela' => 'storage/home/cpu.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Memória',
                'nome_tela' => 'memoria',
                'imagem_tela' => 'storage/home/memoria.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'HD',
                'nome_tela' => 'hd',
                'imagem_tela' => 'storage/home/hd.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'IP Publico',
                'nome_tela' => 'ip_publico',
                'imagem_tela' => 'storage/home/ip_publico.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Busca de IPs',
                'nome_tela' => 'ip',
                'imagem_tela' => 'storage/home/ip.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'SSH',
                'nome_tela' => 'ssh',
                'imagem_tela' => 'n/t',
                'permite_home' => 0,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Usuarios',
                'nome_tela' => 'usuario',
                'imagem_tela' => 'storage/home/usuarios.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Perfis de Usuarios',
                'nome_tela' => 'perfis_usuarios',
                'imagem_tela' => 'storage/home/perfis_usuarios.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Logs de Acesso',
                'nome_tela' => 'access_logs',
                'imagem_tela' => 'storage/home/log_access.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'nome' => 'Scripts',
                'nome_tela' => 'script',
                'imagem_tela' => 'storage/home/terraform.png',
                'permite_home' => 1,
                'created_at' => null,
                'updated_at' => null,
            ],
        ];

        DB::table('home')->insert($dadosPadraoHome);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home');
    }
};
