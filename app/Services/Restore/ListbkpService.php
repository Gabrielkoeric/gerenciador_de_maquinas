<?php

namespace App\Services\Restore;

use App\Repositories\Cliente\ClienteRepository;
use App\Repositories\Restore\ListBkpRepository;
use Carbon\Carbon;

class ListBkpService
{
    public function __construct(
        private ClienteRepository $clienteRepository,
        private ListBkpRepository $listBkpRepository
    ) {
    }

    public function listarBackups(int $idCliente): array
    {
        $cliente = $this->clienteRepository->findById($idCliente);

        if (!$cliente) {
            throw new \RuntimeException('Cliente não encontrado.');
        }

        $backups = $this->listBkpRepository->listarBackups(
            $cliente->apelido
        );

        return [
            'cliente' => $cliente->apelido,

            'full' => $this->montarCadeia(
                $backups['full'],
                $backups['diff'],
                $backups['log']
            ),
        ];
    }


    private function montarCadeia(
        array $fulls,
        array $diffs,
        array $logs
    ): array {

        /*
         * Primeiro agrupamos as partes dos FULLs.
         *
         * Exemplo:
         *
         * full_20260823_2333_1
         * full_20260823_2333_2
         * full_20260823_2333_3
         *
         * vira um único FULL.
         */

        $fullsAgrupados = collect($fulls)
            ->filter(fn ($arquivo) => !empty($arquivo['data_hora']))
            ->groupBy('data_hora')
            ->map(function ($arquivos, $dataHora) {

                $arquivos = $arquivos
                    ->sortBy(function ($arquivo) {

                        preg_match(
                            '/_(\d+)\.bak$/',
                            $arquivo['nome'],
                            $matches
                        );

                        return isset($matches[1])
                            ? (int) $matches[1]
                            : 0;
                    })
                    ->values();

                return [
                    'data_hora' => $dataHora,
                    'quantidade_partes' => $arquivos->count(),
                    'tamanho_total' => $arquivos->sum('tamanho'),
                    'arquivos' => $arquivos->all(),
                ];
            })
            ->sortBy('data_hora')
            ->values()
            ->all();


        /*
         * Ordena DIFFs cronologicamente.
         */

        $diffs = collect($diffs)
            ->filter(fn ($arquivo) => !empty($arquivo['data_hora']))
            ->sortBy('data_hora')
            ->values()
            ->all();


        /*
         * Ordena LOGs cronologicamente.
         */

        $logs = collect($logs)
            ->filter(fn ($arquivo) => !empty($arquivo['data_hora']))
            ->sortBy('data_hora')
            ->values()
            ->all();


        /*
         * Agora construímos a cadeia:
         *
         * FULL
         *   ↓
         * DIFF
         *   ↓
         * LOG
         */

        foreach ($fullsAgrupados as $indiceFull => &$full) {

            $dataFull = Carbon::parse($full['data_hora']);


            /*
             * Descobre o próximo FULL.
             */

            $proximoFull = $fullsAgrupados[$indiceFull + 1] ?? null;

            $dataProximoFull = $proximoFull
                ? Carbon::parse($proximoFull['data_hora'])
                : null;


            /*
             * Pega somente os DIFFs pertencentes
             * a este FULL.
             */

            $diffsDoFull = collect($diffs)
                ->filter(function ($diff) use (
                    $dataFull,
                    $dataProximoFull
                ) {

                    $dataDiff = Carbon::parse(
                        $diff['data_hora']
                    );


                    /*
                     * DIFF precisa ser posterior ao FULL.
                     */

                    if ($dataDiff <= $dataFull) {
                        return false;
                    }


                    /*
                     * Se existe próximo FULL,
                     * o DIFF precisa ser anterior a ele.
                     */

                    if (
                        $dataProximoFull &&
                        $dataDiff >= $dataProximoFull
                    ) {
                        return false;
                    }

                    return true;
                })
                ->values()
                ->all();


            /*
             * Agora vamos colocar os LOGs
             * dentro de cada DIFF.
             */

            foreach ($diffsDoFull as $indiceDiff => &$diff) {

                $dataDiff = Carbon::parse(
                    $diff['data_hora']
                );


                /*
                 * O limite dos LOGs é o próximo DIFF.
                 */

                $proximoDiff = $diffsDoFull[$indiceDiff + 1] ?? null;


                if ($proximoDiff) {

                    $dataProximoDiff = Carbon::parse(
                        $proximoDiff['data_hora']
                    );

                } else {

                    /*
                     * Se não existe próximo DIFF,
                     * usamos o próximo FULL como limite.
                     */

                    $dataProximoDiff = $dataProximoFull;
                }


                /*
                 * Seleciona os LOGs pertencentes
                 * a este intervalo.
                 */

                $logsDoDiff = collect($logs)
                    ->filter(function ($log) use (
                        $dataDiff,
                        $dataProximoDiff
                    ) {

                        $dataLog = Carbon::parse(
                            $log['data_hora']
                        );


                        /*
                         * LOG precisa ser posterior
                         * ao DIFF.
                         */

                        if ($dataLog <= $dataDiff) {
                            return false;
                        }


                        /*
                         * Se existe próximo DIFF/FULL,
                         * LOG precisa estar antes dele.
                         */

                        if (
                            $dataProximoDiff &&
                            $dataLog >= $dataProximoDiff
                        ) {
                            return false;
                        }

                        return true;
                    })
                    ->values()
                    ->all();


                $diff['logs'] = $logsDoDiff;
            }

            unset($diff);


            /*
             * Coloca os DIFFs dentro do FULL.
             */

            $full['diffs'] = $diffsDoFull;
        }

        unset($full);


        return $fullsAgrupados;
    }
}