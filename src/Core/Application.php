<?php

namespace App\Core;

class Application
{
    private Router $router;
    private array $middlewares = [];
    private static Application $instance;

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance(): Application
    {
        return self::$instance;
    }

    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    public function addMiddleware($middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function run(): void
    {
        try {
            // Executar middlewares
            foreach ($this->middlewares as $middleware) {
                $middleware->handle();
            }

            // Processar rota
            $this->router->resolve();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    private function handleException(\Exception $e): void
    {
        $response = [
            'status' => 'ERROR',
            'message' => $e->getMessage(),
            'code' => $e->getCode() ?: 500,
            'timestamp' => date('Y-m-d H:i:s'),
            'path' => $_SERVER['REQUEST_URI'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD'] ?? ''
        ];

        if ($_ENV['APP_DEBUG'] === 'true') {
            $response['trace'] = $e->getTraceAsString();
        }

        http_response_code($response['code']);
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
} 