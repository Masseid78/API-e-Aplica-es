<?php

namespace App\Middleware;

use App\Core\Cache;

class RateLimitMiddleware
{
    private const REQUESTS_PER_HOUR = 100;
    private const WINDOW_SECONDS = 3600;

    public function handle(): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = "rate_limit:{$ip}";
        
        try {
            $cache = new Cache();
            $requests = $cache->get($key) ?? 0;
            
            if ($requests >= self::REQUESTS_PER_HOUR) {
                http_response_code(429);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'ERROR',
                    'message' => 'Rate limit exceeded. Please try again later.',
                    'retry_after' => self::WINDOW_SECONDS
                ]);
                exit;
            }
            
            $cache->set($key, $requests + 1, self::WINDOW_SECONDS);
            
        } catch (\Exception $e) {
            // Se o cache falhar, continuar sem rate limiting
        }
    }
} 