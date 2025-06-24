<?php

// Carregar autoloader simples
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Configurações básicas
$_ENV['APP_NAME'] = 'Impressive API';
$_ENV['APP_ENV'] = 'development';
$_ENV['APP_DEBUG'] = 'true';
$_ENV['JWT_SECRET'] = 'your-super-secret-jwt-key-change-this-in-production';

// Inicializar aplicação
$app = new App\Core\Application();

// Configurar middlewares globais
$app->addMiddleware(new App\Middleware\CorsMiddleware());
$app->addMiddleware(new App\Middleware\RateLimitMiddleware());

// Configurar rotas
$router = new App\Core\Router();

// Rotas públicas
$router->get('/', [App\Controllers\ApiController::class, 'index']);
$router->get('/api/status', [App\Controllers\ApiController::class, 'status']);
$router->get('/api/random', [App\Controllers\ApiController::class, 'random']);
$router->get('/api/weather', [App\Controllers\ApiController::class, 'weather']);
$router->get('/api/crypto', [App\Controllers\ApiController::class, 'crypto']);
$router->get('/api/quote', [App\Controllers\ApiController::class, 'quote']);
$router->get('/api/ip', [App\Controllers\ApiController::class, 'ip']);
$router->get('/api/time', [App\Controllers\ApiController::class, 'time']);
$router->get('/api/validate-email', [App\Controllers\ApiController::class, 'validateEmail']);
$router->get('/api/validate-cpf', [App\Controllers\ApiController::class, 'validateCpf']);
$router->get('/api/cep', [App\Controllers\ApiController::class, 'cep']);
$router->get('/api/translate', [App\Controllers\ApiController::class, 'translate']);
$router->get('/api/currency', [App\Controllers\ApiController::class, 'currency']);

// Rotas de autenticação
$router->post('/api/auth/login', [App\Controllers\AuthController::class, 'login']);
$router->post('/api/auth/register', [App\Controllers\AuthController::class, 'register']);
$router->post('/api/auth/refresh', [App\Controllers\AuthController::class, 'refresh']);

// Rotas protegidas
$router->get('/api/user/profile', [App\Controllers\UserController::class, 'profile']);
$router->put('/api/user/profile', [App\Controllers\UserController::class, 'updateProfile']);
$router->get('/api/user/stats', [App\Controllers\UserController::class, 'stats']);

// Rotas de dados
$router->get('/api/data/analytics', [App\Controllers\DataController::class, 'analytics']);
$router->post('/api/data/upload', [App\Controllers\DataController::class, 'upload']);

// Documentação da API
$router->get('/api/docs', [App\Controllers\ApiController::class, 'docs']);
$router->get('/api/docs.json', [App\Controllers\ApiController::class, 'docsJson']);

// Interface web para testar a API
$router->get('/dashboard', [App\Controllers\ApiController::class, 'dashboard']);

$app->setRouter($router);

// Executar aplicação
$app->run(); 