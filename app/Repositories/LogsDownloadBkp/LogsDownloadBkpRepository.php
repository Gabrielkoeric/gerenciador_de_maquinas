<?php

namespace App\Repositories\LogsDownloadBkp;

use Illuminate\Support\Facades\DB;

class LogsDownloadBkpRepository
{
    public function listarCompleto()
    {
        return DB::table('logs_download_bkp')
            ->join(
                'usuarios',
                'logs_download_bkp.id_usuario',
                '=',
                'usuarios.id'
            )
            ->select(
                'logs_download_bkp.id_logs_download_bkp',
                'logs_download_bkp.objeto',
                'logs_download_bkp.nome_cliente',
                'logs_download_bkp.data_hora',
                'logs_download_bkp.ip_address',
                'logs_download_bkp.id_usuario',
                'usuarios.email'
            )
            ->orderByDesc('logs_download_bkp.data_hora')
            ->paginate(100);
    }
}
