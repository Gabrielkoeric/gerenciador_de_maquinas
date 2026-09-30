<?php

namespace App\Services\Proxmox;

use Illuminate\Support\Facades\Http;
use App\Repositories\ConfigGeral\ConfigGeralRepository;
use App\Repositories\Server\ServerRepository;

class ProxmoxService
{
    protected ConfigGeralRepository $configRepo;
    protected ServerRepository $serverRepo;

    public function __construct(
        ConfigGeralRepository $configRepo,
        ServerRepository $serverRepo
    ) {
        $this->configRepo = $configRepo;
        $this->serverRepo = $serverRepo;
    }

    public function request(
        string $metodo,
        string $rota,
        array $parametros = []
    ): array {

        $token = $this->configRepo->getConfigGeral('tokenApiProxmox');

        $servidores = $this->serverRepo->listarServidores();

        $ultimoErro = null;

        foreach ($servidores as $servidor) {

            $url = 'https://' .
                $servidor->ip_lan .
                ':' .
                $servidor->porta .
                '/api2/json/' .
                ltrim($rota, '/');

            try {

                $http = Http::timeout(5)
                    ->withHeaders([
                        'Authorization' => $token,
                    ])
                    ->withoutVerifying();

                $response = match (strtoupper($metodo)) {
                    'GET' => $http->get($url, $parametros),
                    'POST' => $http->post($url, $parametros),
                    'PUT' => $http->put($url, $parametros),
                    'DELETE' => $http->delete($url, $parametros),
                    'PATCH' => $http->patch($url, $parametros),

                    default => throw new \InvalidArgumentException(
                        "Método HTTP não suportado: {$metodo}"
                    ),
                };

                /*
                 * Se chegou aqui, o servidor respondeu.
                 * Não tenta os próximos.
                 */
                return $response->json();

            } catch (\Throwable $e) {

                /*
                 * Guarda o último erro e tenta o próximo servidor.
                 */
                $ultimoErro = $e;

                continue;
            }
        }

        throw new \RuntimeException(
            'Nenhum servidor Proxmox está disponível.',
            0,
            $ultimoErro
        );
    }
}