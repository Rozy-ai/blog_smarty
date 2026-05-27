<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('#\{([^/]+)\}#', '(?P<$1>[^/]+)', $route);
            // Для маршрута "/" rtrim("/","/") => "" и шаблон превращается в "#^$#".
            // Нормализуем в "/" чтобы корень матчился корректно.
            $pattern = rtrim($pattern, '/');
            $pattern = $pattern === '' ? '/' : $pattern;
            $pattern = '#^' . $pattern . '$#';

            if (!preg_match($pattern, $path, $matches)) {
                continue;
            }

            $params = array_filter($matches, static fn ($key): bool => is_string($key), ARRAY_FILTER_USE_KEY);
            $handler(...array_values($params));
            return;
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}
