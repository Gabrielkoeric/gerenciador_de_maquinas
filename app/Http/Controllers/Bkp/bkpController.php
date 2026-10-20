<?php

namespace App\Http\Controllers\Bkp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;

class bkpController extends Controller
{

    public function index(Request $request)
    {
        $filtroClientes = $request->input('clientes', []);
        $filtroTipos = $request->input('tipos', []);

        $query = DB::table('bkp')
            ->join(
                'cliente_escala',
                'bkp.id_cliente_escala',
                '=',
                'cliente_escala.id_cliente_escala'
            )
            ->select(
                'bkp.*',
                'cliente_escala.apelido',
                'cliente_escala.nome'
            );

        if (!empty($filtroClientes)) {
            $query->whereIn('bkp.id_cliente_escala', $filtroClientes);
        }

        if (!empty($filtroTipos)) {
            $query->whereIn('bkp.tipo', $filtroTipos);
        }

        $bkps = $query
            ->orderBy('bkp.data_backup', 'desc')
            ->paginate(100)
            ->withQueryString();

        $todosClientes = DB::table('cliente_escala')
            ->orderBy('nome')
            ->get();

        $todosTipos = collect([
            (object) ['id' => 'full', 'nome' => 'Full'],
            (object) ['id' => 'diff', 'nome' => 'Diff'],
            (object) ['id' => 'log', 'nome' => 'Log'],
        ]);

        return view('bkp.index')
            ->with('bkps', $bkps)
            ->with('todosClientes', $todosClientes)
            ->with('filtroClientes', $filtroClientes)
            ->with('todosTipos', $todosTipos)
            ->with('filtroTipos', $filtroTipos);
    }

    public function download($id)
    {
        $bkp = DB::table('bkp')
            ->join(
                'cliente_escala',
                'bkp.id_cliente_escala',
                '=',
                'cliente_escala.id_cliente_escala'
            )
            ->where('bkp.id_bkp', $id)
            ->select(
                'bkp.*',
                'cliente_escala.apelido'
            )
            ->first();

        if (!$bkp) {
            abort(404, 'Backup não encontrado.');
        }

        $caminho = 'clientes/' . $bkp->apelido . '_escalasoft/' . $bkp->objeto;

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('IDRIVE_REGION', 'us-east-1'),
            'endpoint' => env('IDRIVE_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'signature_version' => 'v4',
            'credentials' => [
                'key' => env('IDRIVE_ACCESS_KEY'),
                'secret' => env('IDRIVE_SECRET_KEY'),
            ],
        ]);

        $command = $s3->getCommand('GetObject', [
            'Bucket' => env('IDRIVE_BUCKET'),
            'Key' => $caminho,
        ]);

        $request = $s3->createPresignedRequest(
            $command,
            '+1 minute'
        );

        Log::info('Download de backup solicitado', [
            'id_bkp' => $id,
            'arquivo' => $caminho,
        ]);

        return redirect()->away(
            (string) $request->getUri()
        );
    }
}
