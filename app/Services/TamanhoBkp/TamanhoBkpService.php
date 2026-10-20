<?php

namespace App\Services\TamanhoBkp;

use App\Repositories\Bkp\BkpRepository;
use Illuminate\Support\Facades\Log;

class TamanhoBkpService
{
    private const CAMINHO_BACKUPS = '/mnt/backup/clientes';

    public function __construct(
        private BkpRepository $bkpRepository
    ) {
    }

    public function coletar(): array
    {
        $backups = $this->bkpRepository->buscarBackupsSemTamanho();

        $resultado = [
            'consultados' => 0,
            'atualizados' => 0,
            'nao_encontrados' => 0,
            'erros' => 0,
        ];

        foreach ($backups as $bkp) {
            $resultado['consultados']++;

            $caminho = self::CAMINHO_BACKUPS
                . '/'
                . $bkp->apelido
                . '_escalasoft/'
                . ltrim($bkp->objeto, '/');

            clearstatcache(true, $caminho);

            if (!is_file($caminho)) {
                $resultado['nao_encontrados']++;

                Log::warning('Arquivo de backup não encontrado para coleta de tamanho.', [
                    'id_bkp' => $bkp->id_bkp,
                    'id_cliente_escala' => $bkp->id_cliente_escala,
                    'caminho' => $caminho,
                ]);

                continue;
            }

            $tamanho = @filesize($caminho);

            if ($tamanho === false || $tamanho <= 0) {
                $resultado['erros']++;

                Log::warning('Não foi possível obter um tamanho válido do backup.', [
                    'id_bkp' => $bkp->id_bkp,
                    'caminho' => $caminho,
                ]);

                continue;
            }

            try {
                $atualizado = $this->bkpRepository
                    ->atualizarTamanho($bkp->id_bkp, $tamanho);

                if ($atualizado) {
                    $resultado['atualizados']++;
                }
            } catch (\Throwable $e) {
                $resultado['erros']++;

                Log::error('Erro ao atualizar o tamanho do backup.', [
                    'id_bkp' => $bkp->id_bkp,
                    'erro' => $e->getMessage(),
                ]);
            }
        }
        return $resultado;
    }
}