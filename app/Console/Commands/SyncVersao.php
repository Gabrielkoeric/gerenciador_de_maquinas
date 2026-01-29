<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Repositories\WebService\WebServiceRepository;

class SyncVersao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:versao';

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
    public function handle()
    {
        $clientes = app(WebServiceRepository::class)->listarDadosWs();

        foreach ($clientes as $cliente) {

            $rotaBase = $cliente->config_ws ?: $cliente->rota_padrao_ws;

            $url = 'http://'
                . $cliente->vm_nome
                /*. '.cloud.escalasoft.com.br:'*/
                .':'. $cliente->porta_ws
                . $rotaBase
                . $cliente->rota_padrao_ws_cloud;

            try {
                $response = Http::timeout(10)->get($url);
            
                Log::info('Retorno consulta_versao', [
                    'cliente' => $cliente->apelido,
                    'url'     => $url,
                    'status'  => $response->status(),
                    'retorno' => $response->body(), // 👈 bruto
                ]);

                if ($response->status() !== 200) {
                    Log::info('Consulta ignorada (status != 200)', [
                        'cliente' => $cliente->apelido,
                        'url'     => $url,
                        'status'  => $response->status(),
                    ]);

                    continue;
                }
            
                $body = $response->body();

            $body = preg_replace(
                '/("dataatualizacao":)([^,}]+)/',
                '$1"$2"',
                $body
            );
            $body = preg_replace(
                '/("licenciadoate":)([^,}]+)/',
                '$1"$2"',
                $body
            );

            $data = json_decode($body, true);
            
            $versaoRaw        = $data[0]['versao'];
            $dataAtualizacao = $data[0]['dataatualizacao'];
            $licenciadoAte   = $data[0]['licenciadoate'];
            
            $versao = number_format($versaoRaw, 2, '.', '');

            Log::info("retorno $versao, $dataAtualizacao, $licenciadoAte");

            $idVersao = DB::table('versoes')
                ->where('nome', $versao)
                ->value('id_versoes');

            DB::table('cliente_escala')
                ->where('id_cliente_escala', $cliente->id_cliente_escala)
                ->update([
                    'data_atualizacao' => $dataAtualizacao,
                    'licenciado_ate'   => $licenciadoAte,
                    'id_versoes'       => $idVersao,
                    'updated_at'       => now(),
                ]);

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
            
                Log::warning('Timeout consulta_versao', [
                    'cliente' => $cliente->apelido,
                    'url'     => $url,
                    'erro'    => 'timeout'
                ]);
            }
        }
        return Command::SUCCESS;
    }
}