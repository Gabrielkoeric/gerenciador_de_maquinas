<?php

namespace App\Http\Controllers\DocumentacaoGerenciador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class documentacaoGerenciadorController extends Controller
{
    /**
     * Exibe a documentação automática do sistema.
     */
    public function index()
    {
        $path = storage_path('app/documentation/documentation.json');

        if (!File::exists($path)) {
            return view('documentacao_gerenciador.index', [
                'documentation' => collect(),
                'documentacaoGerada' => false,
            ]);
        }

        $documentation = collect(
            json_decode(File::get($path), true) ?? []
        );

        return view('documentacao_gerenciador.index', [
            'documentation' => $documentation,
            'documentacaoGerada' => true,
        ]);
    }

    /**
     * Executa a atualização da documentação.
     */
    public function atualizar()
    {
        Artisan::call('docs:generate');

        return redirect()
            ->route('documentacao_gerenciador.index')
            ->with('success', 'Documentação atualizada com sucesso.');
    }
}
