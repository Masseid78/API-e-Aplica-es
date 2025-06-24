<?php

namespace App\Services;

class ExternalApiService
{
    public function getWeather(string $city): array
    {
        // Simulação de dados meteorológicos
        $conditions = ['Ensolarado', 'Nublado', 'Chuvoso', 'Parcialmente nublado'];
        $condition = $conditions[array_rand($conditions)];
        
        return [
            'city' => $city,
            'temperature' => rand(15, 35),
            'condition' => $condition,
            'humidity' => rand(40, 90),
            'wind_speed' => rand(0, 30),
            'forecast' => [
                'today' => ['temp' => rand(15, 35), 'condition' => $condition],
                'tomorrow' => ['temp' => rand(15, 35), 'condition' => $conditions[array_rand($conditions)]],
                'day_after' => ['temp' => rand(15, 35), 'condition' => $conditions[array_rand($conditions)]]
            ]
        ];
    }

    public function getCryptoPrices(string $currency = 'USD'): array
    {
        $cryptos = [
            'bitcoin' => ['name' => 'Bitcoin', 'symbol' => 'BTC'],
            'ethereum' => ['name' => 'Ethereum', 'symbol' => 'ETH'],
            'cardano' => ['name' => 'Cardano', 'symbol' => 'ADA'],
            'solana' => ['name' => 'Solana', 'symbol' => 'SOL'],
            'polkadot' => ['name' => 'Polkadot', 'symbol' => 'DOT']
        ];

        $prices = [];
        foreach ($cryptos as $key => $crypto) {
            $prices[$key] = [
                'name' => $crypto['name'],
                'symbol' => $crypto['symbol'],
                'price' => round(rand(100, 50000) / 100, 2),
                'change_24h' => round((rand(-50, 50) / 100), 2),
                'market_cap' => rand(1000000000, 1000000000000)
            ];
        }

        return [
            'currency' => $currency,
            'prices' => $prices,
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }

    public function getRandomQuote(): array
    {
        $quotes = [
            ['text' => 'A persistência é o caminho do êxito.', 'author' => 'Charles Chaplin'],
            ['text' => 'O sucesso nasce do querer, da determinação e persistência em se chegar a um objetivo.', 'author' => 'Augusto Cury'],
            ['text' => 'Não espere por uma crise para descobrir o que é importante em sua vida.', 'author' => 'Platão'],
            ['text' => 'A vida é 10% o que acontece com você e 90% como você reage a isso.', 'author' => 'Charles R. Swindoll'],
            ['text' => 'O conhecimento é o investimento que paga os melhores juros.', 'author' => 'Benjamin Franklin']
        ];

        return $quotes[array_rand($quotes)];
    }

    public function getIpInfo(string $ip): array
    {
        return [
            'ip' => $ip,
            'country' => 'Brasil',
            'city' => 'São Paulo',
            'region' => 'SP',
            'timezone' => 'America/Sao_Paulo',
            'isp' => 'Provedor de Internet',
            'coordinates' => ['lat' => -23.5505, 'lon' => -46.6333]
        ];
    }

    public function getCepInfo(string $cep): array
    {
        return [
            'cep' => $cep,
            'logradouro' => 'Rua das Flores',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'ibge' => '3550308'
        ];
    }

    public function translate(string $text, string $from, string $to): array
    {
        $translations = [
            'hello world' => 'olá mundo',
            'olá mundo' => 'hello world',
            'good morning' => 'bom dia',
            'bom dia' => 'good morning'
        ];

        $key = strtolower($text);
        $translated = $translations[$key] ?? $text . ' (traduzido)';

        return [
            'original' => $text,
            'translated' => $translated,
            'from' => $from,
            'to' => $to
        ];
    }

    public function convertCurrency(string $from, string $to, float $amount): array
    {
        $rates = [
            'USD' => ['BRL' => 5.0, 'EUR' => 0.85],
            'BRL' => ['USD' => 0.2, 'EUR' => 0.17],
            'EUR' => ['USD' => 1.18, 'BRL' => 5.88]
        ];

        $rate = $rates[$from][$to] ?? 1.0;
        $converted = $amount * $rate;

        return [
            'from' => ['currency' => $from, 'amount' => $amount],
            'to' => ['currency' => $to, 'amount' => round($converted, 2)],
            'rate' => $rate,
            'date' => date('Y-m-d H:i:s')
        ];
    }
} 