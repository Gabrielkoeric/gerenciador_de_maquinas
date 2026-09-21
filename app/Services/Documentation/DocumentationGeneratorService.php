<?php

namespace App\Services\Documentation;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionMethod;

class DocumentationGeneratorService
{
    public function generate(): array
    {
        $documentation = [];

        foreach (Route::getRoutes() as $route) {
$action = $route->getActionName();

if (!str_contains($action, '@')) {
    continue;
}

[$controller, $method] = explode('@', $action);

if (!str_starts_with($controller, 'App\\Http\\Controllers\\')) {
    continue;
}

if (!class_exists($controller)) {
    continue;
}

            try {
                $reflection = new ReflectionClass($controller);

                if (!$reflection->hasMethod($method)) {
                    continue;
                }

                $reflectionMethod = $reflection->getMethod($method);

                $description = $this->getDescription($reflectionMethod);

                if (!$description) {
                    continue;
                }

                $documentation[] = [
                    'module' => $this->getModuleName($controller),
                    'controller' => $controller,
                    'controller_name' => class_basename($controller),
                    'method' => $method,
                    'description' => $description,
                    'http_method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                ];
            } catch (\Throwable $e) {
                continue;
            }
        }

        $this->save($documentation);

        return $documentation;
    }

    private function getDescription(ReflectionMethod $method): ?string
    {
        $docComment = $method->getDocComment();

        if (!$docComment) {
            return null;
        }

        $docComment = preg_replace('/^\/\*\*|\*\/$/', '', $docComment);

        $lines = explode("\n", $docComment);

        $description = [];

        foreach ($lines as $line) {
            $line = trim($line);
            $line = preg_replace('/^\*\s?/', '', $line);

            if (!$line) {
                continue;
            }

            if (str_starts_with($line, '@')) {
                break;
            }

            $description[] = $line;
        }

        return trim(implode(' ', $description)) ?: null;
    }

    private function getModuleName(string $controller): string
    {
        $controllerName = class_basename($controller);

        return preg_replace('/Controller$/', '', $controllerName);
    }

    private function save(array $documentation): void
    {
        $path = storage_path('app/documentation');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        File::put(
            $path . '/documentation.json',
            json_encode(
                $documentation,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            )
        );
    }
}