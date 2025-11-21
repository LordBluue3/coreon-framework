<?php

declare(strict_types=1);

/**
 * Coreon Framework (c) 2025 Mikael Oliveira
 * Licensed under the MIT License.
 */

namespace App\Controllers;

abstract class BaseController
{
    protected function view(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . '/../../resource/views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            $viewPath = __DIR__ . '/../../resource/views/errors/404.php';

            if (!file_exists($viewPath)) {
                echo "404 - Página não encontrada";
                die;
            }
        }

        extract($data, EXTR_SKIP);

        include $viewPath;
    }
}
