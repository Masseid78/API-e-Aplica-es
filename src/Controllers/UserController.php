<?php

namespace App\Controllers;

use App\Core\Response;

class UserController
{
    public function profile(): void
    {
        // Simulação de dados do usuário
        $data = [
            'id' => 1,
            'name' => 'João Silva',
            'email' => 'joao@exemplo.com',
            'created_at' => '2024-01-01 10:00:00',
            'last_login' => date('Y-m-d H:i:s'),
            'preferences' => [
                'language' => 'pt-BR',
                'timezone' => 'America/Sao_Paulo',
                'notifications' => true
            ]
        ];
        
        Response::success($data, 'Perfil do usuário');
    }

    public function updateProfile(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        
        if (empty($name) || empty($email)) {
            Response::error('Nome e email são obrigatórios', 400);
            return;
        }
        
        // Simulação de atualização
        $data = [
            'id' => 1,
            'name' => $name,
            'email' => $email,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        Response::success($data, 'Perfil atualizado com sucesso');
    }

    public function stats(): void
    {
        $data = [
            'total_requests' => rand(1000, 5000),
            'requests_today' => rand(50, 200),
            'favorite_endpoints' => [
                '/api/random' => rand(100, 500),
                '/api/weather' => rand(50, 200),
                '/api/crypto' => rand(30, 150)
            ],
            'api_usage' => [
                'daily' => rand(80, 120),
                'weekly' => rand(500, 800),
                'monthly' => rand(2000, 3000)
            ]
        ];
        
        Response::success($data, 'Estatísticas do usuário');
    }
} 