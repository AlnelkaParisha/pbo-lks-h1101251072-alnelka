<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable $callback): void
    {
        $this->add('GET', $uri, $callback);
    }

    public function post(string $uri, callable $callback): void
    {
        $this->add('POST', $uri, $callback);
    }

    public function put(string $uri, callable $callback): void
    {
        $this->add('PUT', $uri, $callback);
    }

    public function delete(string $uri, callable $callback): void
    {
        $this->add('DELETE', $uri, $callback);
    }

    private function add(
        string $method,
        string $uri,
        callable $callback
    ): void {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'callback' => $callback
        ];
    }

    public function dispatch(Request $request): void
    {
        foreach ($this->routes as $route) {
            $pattern = preg_replace(
                '/\{([a-zA-Z]+)\}/',
                '([0-9]+)',
                $route['uri']
            );

            if (
                $route['method'] === $request->getMethod()
                && preg_match(
                    '#^' . $pattern . '$#',
                    $request->getUri(),
                    $matches
                )
            ) {
                array_shift($matches);

                $route['callback'](
                    ...array_map('intval', $matches)
                );

                return;
            }
        }

        Response::json([
            'success' => false,
            'message' => 'Endpoint tidak ditemukan: ' . $request->getUri()
        ], 404);
    }
}
