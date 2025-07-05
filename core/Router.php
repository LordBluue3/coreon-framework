<?php

namespace Core;

class Router
{
    private static array $routes = [];

    public static function get(string $uri, callable|array $action)
    {
        self::$routes['GET'][$uri] = $action;
    }

    public static function post(string $uri, callable|array $action)
    {
        self::$routes['POST'][$uri] = $action;
    }

    public static function dispatch(string $requestUri, string $method)
    {
        $uri = parse_url($requestUri, PHP_URL_PATH);

        $action = self::$routes[$method][$uri] ?? null;

        if (!$action) {
            http_response_code(404);
            echo "404 - Not Found";
            return;
        }

        if (is_array($action)) {
            [$controller, $method] = $action;
            $controller = "App\\Controllers\\$controller";
            (new $controller)->$method();
        } else {
            $action();
        }
    }
}
