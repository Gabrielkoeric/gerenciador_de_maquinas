<?php

namespace App\Repositories\Restore;

use Illuminate\Support\Facades\File;

class ListBkpRepository
{
    private string $basePath = '/mnt/backup/clientes';

    public function listarBackups(string $apelido): array
    {
        $clientePath = $this->basePath . '/' . $apelido . '_escalasoft';

        if (!File::isDirectory($clientePath)) {
            return [
                'full' => [],
                'diff' => [],
                'log' => [],
            ];
        }

        return [
            'full' => $this->listarArquivos($clientePath . '/full'),
            'diff' => $this->listarArquivos($clientePath . '/diff'),
            'log' => $this->listarArquivos($clientePath . '/log'),
        ];
    }

    private function listarArquivos(string $path): array
    {
        if (!File::isDirectory($path)) {
            return [];
        }

        return collect(File::files($path))
            ->map(function ($arquivo) {

                $nome = $arquivo->getFilename();

                $dataHora = null;

                if (preg_match(
                    '/_(\d{8})_(\d{4})(?:_\d+)?\.(bak|trn)$/',
                    $nome,
                    $matches
                )) {

                    $data = $matches[1];
                    $hora = $matches[2];

                    $dataHora = \Carbon\Carbon::createFromFormat(
                        'Ymd Hi',
                        $data . ' ' . $hora
                    )->format('Y-m-d H:i:s');
                }

                return [
                    'nome' => $nome,
                    'caminho' => $arquivo->getPathname(),
                    'tamanho' => $arquivo->getSize(),
                    'data_modificacao' => $arquivo->getMTime(),
                    'data_hora' => $dataHora,
                ];
            })
            ->values()
            ->all();
    }
}