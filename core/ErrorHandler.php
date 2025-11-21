<?php

namespace Core;

class ErrorHandler
{
    public static function register(): void
    {
        set_error_handler(function($severity, $message, $file, $line) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        set_exception_handler(function($exception) {
            self::handle($exception);
        });

        register_shutdown_function(function() {
            $error = error_get_last();
            if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                self::handle($error);
            }
        });
    }

    private static function handle($error): void
    {
        http_response_code(500);
        error_log($error instanceof \Throwable ? $error : print_r($error, true));
        include __DIR__ . '/../resource/views/errors/500.php';
        exit;
    }

    public static function init(): void
    {
        self::register();
    }
}

ErrorHandler::init();
