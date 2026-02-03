<?php

namespace App\Jobs\Sql;

use Illuminate\Foundation\Bus\Dispatchable;
use PDO;

class ExecutaSqlServer
{
    use Dispatchable;

    public array $dados;
    public string $sql;

    public function __construct(array $dados, string $sql)
    {
        $this->dados = $dados;
        $this->sql   = $sql;
    }

    public function handle(): array
    {
        $dsn = sprintf(
            "sqlsrv:Server=%s,%d;Database=%s;TrustServerCertificate=1",
            $this->dados['host'],
            $this->dados['port'] ?? 1433,
            $this->dados['database']
        );

        $pdo = new PDO(
            $dsn,
            $this->dados['user'],
            $this->dados['password'],
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        $stmt = $pdo->prepare($this->sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
