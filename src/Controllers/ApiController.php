<?php

namespace App\Controllers;

use App\Core\Response;
use App\Services\ExternalApiService;
use App\Services\ValidationService;

class ApiController
{
    private ExternalApiService $externalApi;
    private ValidationService $validator;

    public function __construct()
    {
        $this->externalApi = new ExternalApiService();
        $this->validator = new ValidationService();
    }

    public function index(): void
    {
        $data = [
            'name' => 'Impressive API',
            'version' => '2.0.0',
            'description' => 'Uma API moderna e impressionante com funcionalidades avançadas',
            'endpoints' => [
                'GET /api/status' => 'Status da API',
                'GET /api/random' => 'Número aleatório',
                'GET /api/weather' => 'Previsão do tempo',
                'GET /api/crypto' => 'Cotações de criptomoedas',
                'GET /api/quote' => 'Citações inspiradoras',
                'GET /api/ip' => 'Informações do IP',
                'GET /api/time' => 'Horário atual',
                'GET /api/validate-email' => 'Validação de email',
                'GET /api/validate-cpf' => 'Validação de CPF',
                'GET /api/cep' => 'Consulta de CEP',
                'GET /api/translate' => 'Tradução de texto',
                'GET /api/currency' => 'Conversão de moedas',
                'GET /api/docs' => 'Documentação da API',
                'GET /dashboard' => 'Interface web'
            ],
            'features' => [
                'Rate Limiting',
                'JWT Authentication',
                'CORS Support',
                'Cache System',
                'Request Logging',
                'Error Handling',
                'Data Validation',
                'External APIs Integration'
            ]
        ];

        Response::success($data, 'Bem-vindo à Impressive API!');
    }

    public function status(): void
    {
        $data = [
            'status' => 'online',
            'uptime' => $this->getUptime(),
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'php_version' => PHP_VERSION,
            'server_time' => date('Y-m-d H:i:s'),
            'timezone' => date_default_timezone_get()
        ];

        Response::success($data, 'API Status');
    }

    public function random(): void
    {
        $min = (int) ($_GET['min'] ?? 0);
        $max = (int) ($_GET['max'] ?? 1000);
        $count = (int) ($_GET['count'] ?? 1);

        if ($count > 100) {
            Response::error('Máximo de 100 números por requisição', 400);
            return;
        }

        $numbers = [];
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = rand($min, $max);
        }

        $data = [
            'numbers' => $count === 1 ? $numbers[0] : $numbers,
            'range' => ['min' => $min, 'max' => $max],
            'count' => $count
        ];

        Response::success($data, 'Número(s) aleatório(s) gerado(s)');
    }

    public function weather(): void
    {
        $city = $_GET['city'] ?? 'São Paulo';
        
        try {
            $weather = $this->externalApi->getWeather($city);
            Response::success($weather, "Previsão do tempo para {$city}");
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function crypto(): void
    {
        $currency = $_GET['currency'] ?? 'USD';
        
        try {
            $crypto = $this->externalApi->getCryptoPrices($currency);
            Response::success($crypto, 'Cotações de criptomoedas');
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function quote(): void
    {
        try {
            $quote = $this->externalApi->getRandomQuote();
            Response::success($quote, 'Citação inspiradora');
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function ip(): void
    {
        $ip = $_GET['ip'] ?? $_SERVER['REMOTE_ADDR'] ?? '8.8.8.8';
        
        try {
            $info = $this->externalApi->getIpInfo($ip);
            Response::success($info, "Informações do IP: {$ip}");
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function time(): void
    {
        $timezone = $_GET['timezone'] ?? 'America/Sao_Paulo';
        
        try {
            $time = new \DateTime('now', new \DateTimeZone($timezone));
            $data = [
                'datetime' => $time->format('Y-m-d H:i:s'),
                'timestamp' => $time->getTimestamp(),
                'timezone' => $timezone,
                'formatted' => [
                    'date' => $time->format('d/m/Y'),
                    'time' => $time->format('H:i:s'),
                    'day' => $time->format('l'),
                    'month' => $time->format('F')
                ]
            ];
            
            Response::success($data, 'Horário atual');
        } catch (\Exception $e) {
            Response::error('Fuso horário inválido', 400);
        }
    }

    public function validateEmail(): void
    {
        $email = $_GET['email'] ?? '';
        
        if (empty($email)) {
            Response::error('Email é obrigatório', 400);
            return;
        }

        $isValid = $this->validator->validateEmail($email);
        $data = [
            'email' => $email,
            'is_valid' => $isValid,
            'suggestions' => $isValid ? [] : $this->validator->getEmailSuggestions($email)
        ];

        Response::success($data, $isValid ? 'Email válido' : 'Email inválido');
    }

    public function validateCpf(): void
    {
        $cpf = $_GET['cpf'] ?? '';
        
        if (empty($cpf)) {
            Response::error('CPF é obrigatório', 400);
            return;
        }

        $isValid = $this->validator->validateCpf($cpf);
        $data = [
            'cpf' => $cpf,
            'is_valid' => $isValid,
            'formatted' => $this->validator->formatCpf($cpf)
        ];

        Response::success($data, $isValid ? 'CPF válido' : 'CPF inválido');
    }

    public function cep(): void
    {
        $cep = $_GET['cep'] ?? '';
        
        if (empty($cep)) {
            Response::error('CEP é obrigatório', 400);
            return;
        }

        try {
            $address = $this->externalApi->getCepInfo($cep);
            Response::success($address, 'Informações do CEP');
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function translate(): void
    {
        $text = $_GET['text'] ?? '';
        $from = $_GET['from'] ?? 'auto';
        $to = $_GET['to'] ?? 'en';
        
        if (empty($text)) {
            Response::error('Texto é obrigatório', 400);
            return;
        }

        try {
            $translation = $this->externalApi->translate($text, $from, $to);
            Response::success($translation, 'Tradução realizada');
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function currency(): void
    {
        $from = $_GET['from'] ?? 'USD';
        $to = $_GET['to'] ?? 'BRL';
        $amount = (float) ($_GET['amount'] ?? 1);
        
        try {
            $conversion = $this->externalApi->convertCurrency($from, $to, $amount);
            Response::success($conversion, 'Conversão de moeda');
        } catch (\Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }

    public function docs(): void
    {
        $html = $this->generateDocsHtml();
        Response::html($html);
    }

    public function docsJson(): void
    {
        $docs = $this->generateOpenApiSpec();
        Response::json($docs);
    }

    public function dashboard(): void
    {
        $html = $this->generateDashboardHtml();
        Response::html($html);
    }

    private function getUptime(): string
    {
        $uptime = time() - strtotime('today');
        $hours = floor($uptime / 3600);
        $minutes = floor(($uptime % 3600) / 60);
        $seconds = $uptime % 60;
        
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    private function generateDocsHtml(): string
    {
        return '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Impressive API - Documentação</title>
            <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.0.0/swagger-ui-bundle.js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.0.0/swagger-ui.css">
            <style>
                body { margin: 0; padding: 20px; background: #f5f5f5; font-family: Arial, sans-serif; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { color: #333; margin: 0; }
                .header p { color: #666; margin: 10px 0; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1> Impressive API</h1>
                <p>Documentação interativa da API</p>
            </div>
            <div id="swagger-ui"></div>
            <script>
                window.onload = function() {
                    SwaggerUIBundle({
                        url: "/api/docs.json",
                        dom_id: "#swagger-ui",
                        presets: [SwaggerUIBundle.presets.apis, SwaggerUIStandalonePreset],
                        layout: "StandaloneLayout"
                    });
                };
            </script>
        </body>
        </html>';
    }

    private function generateDashboardHtml(): string
    {
        return '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Impressive API - Dashboard</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
                .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
                .header { text-align: center; color: white; margin-bottom: 40px; }
                .header h1 { font-size: 3rem; margin-bottom: 10px; }
                .header p { font-size: 1.2rem; opacity: 0.9; }
                .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
                .card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s ease; }
                .card:hover { transform: translateY(-5px); }
                .card h3 { color: #333; margin-bottom: 15px; font-size: 1.3rem; }
                .card p { color: #666; margin-bottom: 20px; line-height: 1.6; }
                .btn { background: linear-gradient(45deg, #667eea, #764ba2); color: white; padding: 12px 25px; border: none; border-radius: 25px; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.3s ease; }
                .btn:hover { transform: scale(1.05); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
                .status { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #4CAF50; margin-right: 10px; }
                .feature-list { list-style: none; }
                .feature-list li { padding: 8px 0; border-bottom: 1px solid #eee; }
                .feature-list li:last-child { border-bottom: none; }
                .api-test { background: #f8f9fa; border-radius: 10px; padding: 20px; margin-top: 20px; }
                .api-test input, .api-test select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
                .result { background: #e8f5e8; border: 1px solid #4CAF50; border-radius: 5px; padding: 15px; margin-top: 15px; display: none; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1> Impressive API</h1>
                    <p>Dashboard Interativo - Teste todos os endpoints da API</p>
                </div>
                
                <div class="grid">
                    <div class="card">
                        <h3> Status da API</h3>
                        <p>Verifique o status atual da API e informações do servidor.</p>
                        <button class="btn" onclick="testEndpoint(\'/api/status\')">Testar Status</button>
                        <div class="api-test">
                            <h4>Endpoint: GET /api/status</h4>
                            <div class="result" id="status-result"></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3> Números Aleatórios</h3>
                        <p>Gere números aleatórios com range personalizado.</p>
                        <div class="api-test">
                            <input type="number" id="min" placeholder="Valor mínimo" value="1">
                            <input type="number" id="max" placeholder="Valor máximo" value="100">
                            <input type="number" id="count" placeholder="Quantidade" value="1">
                            <button class="btn" onclick="testRandom()">Gerar Números</button>
                            <div class="result" id="random-result"></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3> Previsão do Tempo</h3>
                        <p>Obtenha informações meteorológicas de qualquer cidade.</p>
                        <div class="api-test">
                            <input type="text" id="city" placeholder="Nome da cidade" value="São Paulo">
                            <button class="btn" onclick="testWeather()">Consultar Tempo</button>
                            <div class="result" id="weather-result"></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3> Criptomoedas</h3>
                        <p>Consulte cotações em tempo real das principais criptomoedas.</p>
                        <div class="api-test">
                            <select id="currency">
                                <option value="USD">USD</option>
                                <option value="BRL">BRL</option>
                                <option value="EUR">EUR</option>
                            </select>
                            <button class="btn" onclick="testCrypto()">Consultar Criptos</button>
                            <div class="result" id="crypto-result"></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3> Citações Inspiradoras</h3>
                        <p>Receba citações motivacionais aleatórias.</p>
                        <button class="btn" onclick="testEndpoint(\'/api/quote\')">Obter Citação</button>
                        <div class="api-test">
                            <div class="result" id="quote-result"></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3> Informações de IP</h3>
                        <p>Obtenha informações detalhadas sobre endereços IP.</p>
                        <div class="api-test">
                            <input type="text" id="ip" placeholder="Endereço IP" value="8.8.8.8">
                            <button class="btn" onclick="testIp()">Consultar IP</button>
                            <div class="result" id="ip-result"></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3> Validação de Email</h3>
                        <p>Valide endereços de email com sugestões de correção.</p>
                        <div class="api-test">
                            <input type="email" id="email" placeholder="Digite um email" value="teste@exemplo.com">
                            <button class="btn" onclick="testEmail()">Validar Email</button>
                            <div class="result" id="email-result"></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3> Validação de CPF</h3>
                        <p>Valide números de CPF brasileiros.</p>
                        <div class="api-test">
                            <input type="text" id="cpf" placeholder="Digite um CPF" value="12345678909">
                            <button class="btn" onclick="testCpf()">Validar CPF</button>
                            <div class="result" id="cpf-result"></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3> Consulta de CEP</h3>
                        <p>Obtenha informações de endereço pelo CEP.</p>
                        <div class="api-test">
                            <input type="text" id="cep" placeholder="Digite um CEP" value="01001000">
                            <button class="btn" onclick="testCep()">Consultar CEP</button>
                            <div class="result" id="cep-result"></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3> Tradução</h3>
                        <p>Traduza textos entre diferentes idiomas.</p>
                        <div class="api-test">
                            <input type="text" id="translate-text" placeholder="Texto para traduzir" value="Hello world">
                            <select id="from-lang">
                                <option value="en">Inglês</option>
                                <option value="pt">Português</option>
                                <option value="es">Espanhol</option>
                            </select>
                            <select id="to-lang">
                                <option value="pt">Português</option>
                                <option value="en">Inglês</option>
                                <option value="es">Espanhol</option>
                            </select>
                            <button class="btn" onclick="testTranslate()">Traduzir</button>
                            <div class="result" id="translate-result"></div>
                        </div>
                    </div>

                    <div class="card">
                        <h3> Conversão de Moeda</h3>
                        <p>Converta valores entre diferentes moedas.</p>
                        <div class="api-test">
                            <input type="number" id="amount" placeholder="Valor" value="1">
                            <select id="from-currency">
                                <option value="USD">USD</option>
                                <option value="BRL">BRL</option>
                                <option value="EUR">EUR</option>
                            </select>
                            <select id="to-currency">
                                <option value="BRL">BRL</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>
                            <button class="btn" onclick="testCurrency()">Converter</button>
                            <div class="result" id="currency-result"></div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                async function testEndpoint(endpoint) {
                    try {
                        const response = await fetch(endpoint);
                        const data = await response.json();
                        showResult(endpoint.replace("/api/", "") + "-result", data);
                    } catch (error) {
                        showResult(endpoint.replace("/api/", "") + "-result", { error: error.message });
                    }
                }

                async function testRandom() {
                    const min = document.getElementById("min").value;
                    const max = document.getElementById("max").value;
                    const count = document.getElementById("count").value;
                    const url = `/api/random?min=${min}&max=${max}&count=${count}`;
                    await testEndpoint(url);
                }

                async function testWeather() {
                    const city = document.getElementById("city").value;
                    const url = `/api/weather?city=${encodeURIComponent(city)}`;
                    await testEndpoint(url);
                }

                async function testCrypto() {
                    const currency = document.getElementById("currency").value;
                    const url = `/api/crypto?currency=${currency}`;
                    await testEndpoint(url);
                }

                async function testIp() {
                    const ip = document.getElementById("ip").value;
                    const url = `/api/ip?ip=${ip}`;
                    await testEndpoint(url);
                }

                async function testEmail() {
                    const email = document.getElementById("email").value;
                    const url = `/api/validate-email?email=${encodeURIComponent(email)}`;
                    await testEndpoint(url);
                }

                async function testCpf() {
                    const cpf = document.getElementById("cpf").value;
                    const url = `/api/validate-cpf?cpf=${cpf}`;
                    await testEndpoint(url);
                }

                async function testCep() {
                    const cep = document.getElementById("cep").value;
                    const url = `/api/cep?cep=${cep}`;
                    await testEndpoint(url);
                }

                async function testTranslate() {
                    const text = document.getElementById("translate-text").value;
                    const from = document.getElementById("from-lang").value;
                    const to = document.getElementById("to-lang").value;
                    const url = `/api/translate?text=${encodeURIComponent(text)}&from=${from}&to=${to}`;
                    await testEndpoint(url);
                }

                async function testCurrency() {
                    const amount = document.getElementById("amount").value;
                    const from = document.getElementById("from-currency").value;
                    const to = document.getElementById("to-currency").value;
                    const url = `/api/currency?amount=${amount}&from=${from}&to=${to}`;
                    await testEndpoint(url);
                }

                function showResult(elementId, data) {
                    const element = document.getElementById(elementId);
                    element.style.display = "block";
                    element.innerHTML = "<pre>" + JSON.stringify(data, null, 2) + "</pre>";
                }
            </script>
        </body>
        </html>';
    }

    private function generateOpenApiSpec(): array
    {
        return [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Impressive API',
                'version' => '2.0.0',
                'description' => 'Uma API moderna e impressionante com funcionalidades avançadas'
            ],
            'servers' => [
                ['url' => 'http://localhost:8000']
            ],
            'paths' => [
                '/api/status' => [
                    'get' => [
                        'summary' => 'Status da API',
                        'responses' => [
                            '200' => [
                                'description' => 'Status da API',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'status' => ['type' => 'string'],
                                                'data' => ['type' => 'object']
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                '/api/random' => [
                    'get' => [
                        'summary' => 'Gerar números aleatórios',
                        'parameters' => [
                            ['name' => 'min', 'in' => 'query', 'schema' => ['type' => 'integer']],
                            ['name' => 'max', 'in' => 'query', 'schema' => ['type' => 'integer']],
                            ['name' => 'count', 'in' => 'query', 'schema' => ['type' => 'integer']]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Números aleatórios gerados'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
} 