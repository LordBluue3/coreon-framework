<?php

declare(strict_types=1);

/**
 * Coreon Framework (c) 2025 Mikael Oliveira
 * Licensed under the MIT License.
 */

namespace Core;

class Request
{
    public function input(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_REQUEST;
        }
    
        return $_REQUEST[$key] ?? $default;
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function uri(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}