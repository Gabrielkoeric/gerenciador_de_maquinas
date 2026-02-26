<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Repositories\RcloneExecucao\RcloneExecucaoRepository;
use App\Repositories\Repositorio\RepositorioRepository;

use App\Jobs\Rclone\RcloneJobDiario;

class BkpAnexoDiario extends Command
{
    protected $signature = 'bkp:diario';

    protected $description = 'Command description';

    protected $repositorioRepository;
    protected $execucaoRepository;

    public function __construct(
        RepositorioRepository $repositorioRepository,
        RcloneExecucaoRepository $execucaoRepository
    ) {
        parent::__construct();
        $this->repositorioRepository = $repositorioRepository;
        $this->execucaoRepository = $execucaoRepository;
    }

    public function handle()
    {
        $repositorios = $this->repositorioRepository->getRepositoriosDiariosArquivo()

        foreach ($repositorios as $repositorio) {

            $idExecucao = $this->execucaoRepository->criarExecucaoDiaria($repositorio->id_repositorios);

            RcloneJobDiario::dispatch($idExecucao)->onQueue('diario');
        }
    }
}
