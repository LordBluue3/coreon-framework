<?php

declare(strict_types=1);

/**
 * Coreon Framework (c) 2025 Mikael Oliveira
 * Licensed under the MIT License.
 */

namespace Core\Utils;

class Color
{
    public static string $reset  = "\033[0m";
    public static string $green  = "\033[32m";
    public static string $yellow = "\033[33m";
    public static string $red    = "\033[31m";
    public static string $blue   = "\033[34m";

    public static function wrap(string $text, string $color): string
    {
        return $color . $text . self::$reset;
    }
}
