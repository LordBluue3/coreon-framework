<?php

declare(strict_types=1);

/**
 * Coreon Framework (c) 2025 Mikael Oliveira
 * Licensed under the MIT License.
 */

namespace App\Classes;

use Core\Utils\Color;

class Ion{

    public static function handle(array $args): void
    {
        $command = $args[1] ?? null;
        $name = $args[2] ?? null;

        $isCommand = self::isCommand($command);

        if($isCommand === 'invalid'){
            echo Color::wrap("Invalid Command: $command\n", Color::$red);
           die(); 
        }

        if(!$name){
            echo Color::wrap("Name of $isCommand: ", Color::$yellow);
            $name = trim(fgets(STDIN));
        }

        match($isCommand)
        {
            'controller' => self::createController($name),
            'model' => self::createModel($name)
        };
   
    }

    private static function isCommand(?string $command): string
    {
        return match($command){
            'make:controller' => 'controller',
            'make:model' => 'model',
            default => 'invalid',
        };
    }

    private static function createController(string $name) : void
    {
        $stubPath = __DIR__.'/../../core/Stubs/controller.php.stub';

        $content = file_get_contents($stubPath);
        $content = str_replace('{{name}}', basename($name), $content);
        $content = str_replace('{{namespace}}', dirname($name) != '.' ? '\\'.dirname($name): '', $content);
        $filePath = __DIR__ . '/../Controllers/' . $name . '.php';

        if (!is_dir($filePath)) {
            $dir = dirname($filePath);
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

    private static function createModel(string $name) : void
    {
        $stubPath = __DIR__ . '/../../core/Stubs/model.php.stub';

        $content = file_get_contents($stubPath);
        $content = str_replace('{{name}}', basename($name), $content);
        $content = str_replace('{{namespace}}', dirname($name) != '.' ? '\\' . str_replace('/', '\\', dirname($name)) : '', $content);
    
        $filePath = __DIR__ . '/../Models/' . str_replace('\\', '/', $name) . '.php';
    
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
}