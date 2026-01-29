<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Versoes\VersoesRepository;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Repositories\ConfigGeral\ConfigGeralRepository;

class SyncWeb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:web';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

    protected VersoesRepository $versoesRepository;
    protected ConfigGeralRepository $configRepo;

    public function __construct(
        VersoesRepository $versoesRepository,
        ConfigGeralRepository $configRepo
    ) {
        parent::__construct();
        $this->versoesRepository = $versoesRepository;
        $this->configRepo = $configRepo;
    }
    
    public function handle()
    {
        $versoes = $this->versoesRepository->listarNomeVersoes();

        $srcBase = $this->configRepo->getConfigGeral('src_web');
        $dstBase = $this->configRepo->getConfigGeral('dst_web');

        $logFile = "{$dstBase}/rclone.log";
        file_put_contents($logFile, '');

        foreach ($versoes as $versao) {

            $versaoNome = is_object($versao) ? $versao->nome : $versao;

            $this->info("Sincronizando {$versaoNome}");

            $process = new Process([
                'rclone',
                'sync',
                "{$srcBase}{$versaoNome}/Web/EscalaWeb/",
                "{$dstBase}{$versaoNome}/",
                '--log-file=' . $logFile,
                '--log-level=INFO',
            ]);

            Log::info('Comando executado', [
                'command' => $process->getCommandLine(),
            ]);

            $process->setTimeout(null);
            $process->run();
        }
        return Command::SUCCESS;
    }
}
