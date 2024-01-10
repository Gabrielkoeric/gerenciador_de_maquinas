<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 1000000; $i++) {
            DB::table('usuarios')->insert([
                'email' => 'usuario' . $i . '@example.com',
                'nome_completo' => 'Usuario ' . $i,
                'celular' => '123456789',
                'imagem' => 'http://example.com/image' . $i . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
