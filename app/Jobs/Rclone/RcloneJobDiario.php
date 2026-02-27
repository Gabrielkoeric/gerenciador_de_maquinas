<?php

namespace App\Jobs\Rclone;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\RcloneExecucaoDiario\RcloneExecucaoService;

class RcloneJobDiario implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $idExecucao;

    public function __construct(int $idExecucao)
    {
        $this->idExecucao = $idExecucao;
    }

    public function handle(RcloneExecucaoService $service)
    {
        $service->executar($this->idExecucao);
    }
}
