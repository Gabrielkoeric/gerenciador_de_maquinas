<?php

namespace App\Console\Commands;

use App\Services\Documentation\DocumentationGeneratorService;
use Illuminate\Console\Command;

class GenerateDocumentation extends Command
{
    protected $signature = 'docs:generate';

    protected $description = 'Gera a documentação automática do sistema';

    public function handle(DocumentationGeneratorService $generator): int
    {
        $this->info('Gerando documentação...');

        $documentation = $generator->generate();

        $this->info(
            'Documentação gerada com sucesso. '
            . count($documentation)
            . ' rotinas documentadas.'
        );

        return self::SUCCESS;
    }
}
