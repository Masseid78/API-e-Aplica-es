<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, array $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function put(string $path, array $callback): void
    {
        $this->routes['PUT'][$path] = $callback;
    }

    public function delete(string $path, array $callback): void
    {
        $this->routes['DELETE'][$path] = $callback;
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remover trailing slash
        $path = rtrim($path, '/');
        if (empty($path)) {
            $path = '/';
        }

        // Verificar se a rota existe
        if (!isset($this->routes[$method][$path])) {
            // Tentar encontrar rota com parâmetros
            $callback = $this->findRouteWithParams($method, $path);
            if (!$callback) {
                throw new \Exception("Route not found: {$method} {$path}", 404);
            }
        } else {
            $callback = $this->routes[$method][$path];
        }

        // Executar controller
        $controller = new $callback[0]();
        $action = $callback[1];
        
        return $controller->$action();
    }

    private function findRouteWithParams(string $method, string $path): ?array
    {
        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            $pattern = $this->convertRouteToRegex($route);
            if (preg_match($pattern, $path, $matches)) {
                // Extrair parâmetros da URL
                array_shift($matches); // Remove o match completo
                $params = array_values($matches);
                
                // Armazenar parâmetros para uso no controller
                $_GET['route_params'] = $params;
                
                return $callback;
            }
        }
        
        return null;
    }

    private function convertRouteToRegex(string $route): string
    {
        return '#^' . preg_replace('#\{([a-zA-Z]+)\}#', '([^/]+)', $route) . '$#';
    }
} 