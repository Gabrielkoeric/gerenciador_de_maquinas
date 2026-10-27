<?php

namespace App\Http\Controllers\LogsDownloadBkp;

use App\Http\Controllers\Controller;
use App\Repositories\LogsDownloadBkp\LogsDownloadBkpRepository;

class logsDownloadBkpController extends Controller
{
    protected $logsDownloadBkpRepository;

    public function __construct(
        LogsDownloadBkpRepository $logsDownloadBkpRepository
    ) {
        $this->logsDownloadBkpRepository = $logsDownloadBkpRepository;
    }

    public function index()
    {
        $logs = $this->logsDownloadBkpRepository->listarCompleto();

        return view('logsDownloadBkp.index', compact('logs'));
    }
}