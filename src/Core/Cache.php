<?php

namespace App\Core;

class Cache
{
    // Cache em memória
    private static array $memoryCache = [];
    private static array $memoryExpiry = [];

    public function get(string $key)
    {
        if (!isset(self::$memoryCache[$key])) {
            return null;
        }
        
        if (time() > self::$memoryExpiry[$key]) {
            unset(self::$memoryCache[$key], self::$memoryExpiry[$key]);
            return null;
        }
        
        return self::$memoryCache[$key];
    }

    public function set(string $key, $value, int $ttl = 3600): bool
    {
        self::$memoryCache[$key] = $value;
        self::$memoryExpiry[$key] = time() + $ttl;
        return true;
    }

    public function delete(string $key): bool
    {
        unset(self::$memoryCache[$key], self::$memoryExpiry[$key]);
        return true;
    }

    public function exists(string $key): bool
    {
        return isset(self::$memoryCache[$key]) && time() <= self::$memoryExpiry[$key];
    }
} 