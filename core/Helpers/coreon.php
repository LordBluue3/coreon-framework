<?php

declare(strict_types=1);

/**
 * Coreon Framework (c) 2025 Mikael Oliveira
 * Licensed under the MIT License.
 */

function dd(mixed $var): void
{
    (new \Core\Dumper())->dump($var);
    die;
}

function env(string $key, $default = null): string|null
{
    static $vars = null;

    if ($vars === null) {
        $vars = [];
        foreach (file(base_path('.env')) as $line) {
            if (trim($line) === '' || str_starts_with(trim($line), '#')) continue;
            [$k, $v] = explode('=', trim($line), 2);
            $vars[$k] = $v;
        }
    }

    return $vars[$key] ?? $default;
}

function base_path(string $path = ''): string
{
    return dirname(__DIR__, 2) . '/' . ltrim($path, '/');
}

function bcrypt(string $plain, int $cost = 12): string
{
    return password_hash($plain, PASSWORD_BCRYPT, ['cost' => $cost]);
}

function bcrypt_verify(string $plain, string $hash): bool
{
    return password_verify($plain, $hash);
}