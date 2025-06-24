<?php

namespace App\Core;

class Response
{
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public static function success($data = null, string $message = 'Success', int $statusCode = 200): void
    {
        $response = [
            'status' => 'SUCCESS',
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => uniqid()
        ];

        self::json($response, $statusCode);
    }

    public static function error(string $message, int $statusCode = 400, $data = null): void
    {
        $response = [
            'status' => 'ERROR',
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
            'request_id' => uniqid()
        ];

        self::json($response, $statusCode);
    }

    public static function html(string $content, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: text/html; charset=utf-8');
        echo $content;
    }
} 