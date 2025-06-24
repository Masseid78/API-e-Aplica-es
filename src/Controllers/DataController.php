<?php

namespace App\Controllers;

use App\Core\Response;

class DataController
{
    public function analytics(): void
    {
        $data = [
            'total_requests' => rand(10000, 50000),
            'unique_users' => rand(1000, 5000),
            'popular_endpoints' => [
                ['endpoint' => '/api/random', 'requests' => rand(5000, 15000)],
                ['endpoint' => '/api/weather', 'requests' => rand(3000, 8000)],
                ['endpoint' => '/api/crypto', 'requests' => rand(2000, 6000)],
                ['endpoint' => '/api/status', 'requests' => rand(1000, 3000)]
            ],
            'response_times' => [
                'average' => rand(50, 200),
                'min' => rand(10, 50),
                'max' => rand(300, 800)
            ],
            'error_rate' => rand(1, 5) / 100,
            'uptime' => rand(99, 100)
        ];
        
        Response::success($data, 'Analytics da API');
    }

    public function upload(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['data'])) {
            Response::error('Dados são obrigatórios', 400);
            return;
        }
        
        $data = [
            'id' => uniqid(),
            'size' => strlen(json_encode($input['data'])),
            'uploaded_at' => date('Y-m-d H:i:s'),
            'status' => 'processed'
        ];
        
        Response::success($data, 'Dados processados com sucesso', 201);
    }
} 