<?php

declare(strict_types=1);

/**
 * Coreon Framework (c) 2025 Mikael Oliveira
 * Licensed under the MIT License.
 */

namespace Core;

use ReflectionMethod;
use Core\Request;

class Router
{
    private static array $routes = [];

    public static function get(string $uri, callable|array $action): void
    {
        self::$routes['GET'][$uri] = $action;
    }

    public static function post(string $uri, callable|array $action): void
    {
        self::$routes['POST'][$uri] = $action;
    }

    public static function dispatch(string $requestUri, string $httpMethod): void
    {
        $uri = parse_url($requestUri, PHP_URL_PATH);

        $action = self::$routes[$httpMethod][$uri] ?? null;

        if (!$action) {

            http_response_code(404);
            $viewPath = __DIR__ . '/../resource/views/errors/404.php';

            if (!file_exists($viewPath)) {
                echo "404 - Página não encontrada";
                die;
            }
            require $viewPath;
            die();
        }

        if (is_array($action)) {
            [$controller, $method] = $action;

            if (!class_exists($controller)) {
                http_response_code(500);
                echo "Controller $controller not exist.";
                die();
            }

            $instance = new $controller;

            if (!method_exists($instance, $method)) {
                http_response_code(500);
                echo "Method $method not found in $controller.";
                die();
            }

            $reflection = new ReflectionMethod($instance, $method);
            $params = $reflection->getParameters();

            if (count($params) > 0 && $params[0]->getType()?->getName() === Request::class) {
                $instance->$method(new Request());
            } else {
                $instance->$method();
            }
        } else {
            $action();
        }
    }
}
