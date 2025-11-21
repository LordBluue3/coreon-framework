<?php

declare(strict_types=1);

/**
 * Coreon Framework (c) 2025 Mikael Oliveira
 * Licensed under the MIT License.
 */

namespace Core;

use Core\Utils\Color;
use RuntimeException;

class Ion
{
    public static function handle(array $args): void
    {
        $command = $args[1] ?? null;
        $name = $args[2] ?? null;

        $isCommand = self::isCommand($command);

        self::warningMessage($isCommand, $name, $command);

        match ($isCommand) {
            'controller' => self::createController($name),
            'model' => self::createModel($name),
            'serve' => self::startServer()
        };
    }

    private static function isCommand(?string $command): string
    {
        return match ($command) {
            'make:controller' => 'controller',
            'make:model' => 'model',
            'serve' => 'serve',
            default => 'invalid',
        };
    }

    private static function warningMessage($isCommand, $name, $command)
    {
        if ($isCommand === 'invalid') {
            echo Color::wrap("Invalid Command: $command\n", Color::$red);
            die();
        }

        if (!$name && $isCommand !== 'serve' && $isCommand !== 'bench') {
            echo Color::wrap("Name of $isCommand: ", Color::$yellow);
            $name = trim(fgets(STDIN));
        }
    }

    private static function createController(string $name): void
    {
        $stubPath = __DIR__ . '/Stubs/controller.php.stub';

        $content = file_get_contents($stubPath);
        $content = str_replace('{{name}}', basename($name), $content);
        $content = str_replace('{{namespace}}', dirname($name) != '.' ? '\\' . dirname($name) : '', $content);
        $filePath = __DIR__ . '/../app/Controllers/' . $name . '.php';

        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_exists($filePath)) {
            echo Color::wrap("Controller exist: $filePath\n", Color::$yellow);
            die();
        }

        file_put_contents($filePath, $content);
        echo Color::wrap("Controller created: $filePath\n", Color::$green);
        die();
    }

    private static function createModel(string $name): void
    {
        $stubPath = __DIR__ . '/Stubs/model.php.stub';

        $content = file_get_contents($stubPath);
        $content = str_replace('{{name}}', basename($name), $content);
        $content = str_replace('{{namespace}}', dirname($name) != '.' ? '\\' . str_replace('/', '\\', dirname($name)) : '', $content);

        $filePath = __DIR__ . '/../app/Models/' . str_replace('\\', '/', $name) . '.php';

        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_exists($filePath)) {
            echo Color::wrap("Model exists: $filePath\n", Color::$yellow);
            die();
        }

        file_put_contents($filePath, $content);
        echo Color::wrap("Model created: $filePath\n", Color::$green);
        die();
    }

    private static function startServer(): void
    {
        $publicPath = realpath(__DIR__ . '/../../public');

        if ($publicPath === false) {
            throw new RuntimeException('Public path não encontrado.');
        }

        $hasDdev = trim((string) shell_exec('command -v ddev')) !== '';

        if ($hasDdev) {
            $json = shell_exec('ddev describe --json-output 2>/dev/null');

            $data = $json ? json_decode($json, true) : null;
            $status = $data['raw']['status'] ?? null;

            if ($status === 'running') {
                echo "DDEV já está rodando!\n";
                return;
            }

            exec('ddev start');
            echo "DDEV iniciado!\n";
            return;
        }

        echo "DDEV não encontrado. Iniciando com php -S...\n";

        $cmd = sprintf(
            'php -S localhost:8000 -t %s',
            escapeshellarg($publicPath)
        );

        exec($cmd);
    }
}
