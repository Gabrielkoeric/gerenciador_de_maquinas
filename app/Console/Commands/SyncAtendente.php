<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\Notificacao\Telegram;
use Carbon\Carbon;

use App\Repositories\ConfigGeral\ConfigGeralRepository;

class SyncAtendente extends Command
{
    protected $signature = 'plantao:suporte';

    protected $description = 'Sincroniza plantões da API do Escala';

    protected ConfigGeralRepository $configRepo;

    public function __construct(
        ConfigGeralRepository $configRepo
    ) {
        parent::__construct();
        $this->configRepo = $configRepo;
    }

    public function handle()
    {
        try {
            $url = $this->configRepo->getConfigGeral('url_api_24');
            Log::info("url api dos atendente 24 é: $url");
            $response = Http::timeout(30)->get($url);
            
            if (!$response->successful()) {
                Log::error('Erro ao consultar API', [
                    'status' => $response->status()
                ]);
                return 1;
            }

            DB::table('usuarios_plantoes')
                ->where('origem', 'api')
                ->where('inicio', '>', now())
                ->delete();
            

            $dados = $response->json();

            foreach ($dados as $plantao) {

                $usuario = DB::table('usuarios')
                    ->where('email', 'like', $plantao['recurso'] . '@%')
                    ->first();

                if (!$usuario) {
                    Log::warning('Usuário não encontrado', [
                        'recurso' => $plantao['recurso']
                    ]);
                    Telegram::dispatch("⚠️ Usuário não encontrado no sistema GDT.\n\nRecurso: {$plantao['recurso']}", 'telegram24');
                    continue;
                }

                $inicio = Carbon::createFromFormat(
                    'd/m/Y H:i',
                    $plantao['inicio']
                )->format('Y-m-d H:i:s');

                $fim = Carbon::createFromFormat(
                    'd/m/Y H:i',
                    $plantao['termino']
                )->format('Y-m-d H:i:s');

                DB::table('usuarios_plantoes')->insert([
                    'inicio' => $inicio,
                    'fim' => $fim,
                    'handle' => $plantao['handle'],
                    'origem' => 'api',
                    'id' => $usuario->id
                ]);
            }

        } catch (\Exception $e) {

            Log::error('Erro geral na sincronização', [
                'mensagem' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine()
            ]);
            return 1;
        }
        return 0;
    }
}