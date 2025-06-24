<?php

namespace App\Controllers;

use App\Core\Response;

class AuthController
{
    public function login(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            Response::error('Email e senha são obrigatórios', 400);
            return;
        }
        
        // Simulação de autenticação
        if ($email === 'admin@api.com' && $password === '123456') {
            $token = $this->generateSimpleToken(['user_id' => 1, 'email' => $email]);
            
            Response::success([
                'token' => $token,
                'user' => [
                    'id' => 1,
                    'email' => $email,
                    'name' => 'Administrador'
                ]
            ], 'Login realizado com sucesso');
        } else {
            Response::error('Credenciais inválidas', 401);
        }
    }

    public function register(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';
        
        if (empty($name) || empty($email) || empty($password)) {
            Response::error('Todos os campos são obrigatórios', 400);
            return;
        }
        
        // Simulação de registro
        $token = $this->generateSimpleToken(['user_id' => rand(100, 999), 'email' => $email]);
        
        Response::success([
            'token' => $token,
            'user' => [
                'id' => rand(100, 999),
                'email' => $email,
                'name' => $name
            ]
        ], 'Usuário registrado com sucesso', 201);
    }

    public function refresh(): void
    {
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? '';
        
        if (empty($token)) {
            Response::error('Token não fornecido', 401);
            return;
        }
        
        $token = str_replace('Bearer ', '', $token);
        
        try {
            $decoded = $this->decodeSimpleToken($token);
            $newToken = $this->generateSimpleToken(['user_id' => $decoded['user_id'], 'email' => $decoded['email']]);
            
            Response::success(['token' => $newToken], 'Token renovado com sucesso');
        } catch (\Exception $e) {
            Response::error('Token inválido', 401);
        }
    }

    private function generateSimpleToken(array $payload): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + (60 * 60 * 24); // 24 horas
        
        return base64_encode(json_encode($payload));
    }

    private function decodeSimpleToken(string $token): array
    {
        $decoded = json_decode(base64_decode($token), true);
        
        if (!$decoded || !isset($decoded['exp']) || $decoded['exp'] < time()) {
            throw new \Exception('Token inválido ou expirado');
        }
        
        return $decoded;
    }
} 