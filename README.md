# 🚀 Impressive API

Uma API moderna e impressionante com funcionalidades avançadas, construída em PHP puro com arquitetura MVC.

## ✨ Características

- **Arquitetura Moderna**: MVC com namespaces e autoload
- **Autenticação JWT**: Sistema completo de autenticação
- **Rate Limiting**: Proteção contra spam
- **Cache System**: Redis com fallback em memória
- **CORS Support**: Suporte completo a CORS
- **Validação Robusta**: Validação de dados brasileiros (CPF, CEP, etc.)
- **Documentação Interativa**: Swagger/OpenAPI
- **Dashboard Web**: Interface para testar a API
- **Logs Estruturados**: Sistema de logging avançado
- **Error Handling**: Tratamento de erros profissional

## 🛠️ Instalação

1. **Clone o repositório**
```bash
git clone <url-do-repositorio>
cd impressive-api
```

2. **Instale as dependências**
```bash
composer install
```

3. **Configure as variáveis de ambiente**
```bash
cp env.example .env
# Edite o arquivo .env com suas configurações
```

4. **Inicie o servidor**
```bash
composer serve
# ou
php -S localhost:8000 -t public
```

## 📚 Endpoints Disponíveis

### Públicos
- `GET /` - Informações da API
- `GET /api/status` - Status da API
- `GET /api/random` - Números aleatórios
- `GET /api/weather` - Previsão do tempo
- `GET /api/crypto` - Cotações de criptomoedas
- `GET /api/quote` - Citações inspiradoras
- `GET /api/ip` - Informações de IP
- `GET /api/time` - Horário atual
- `GET /api/validate-email` - Validação de email
- `GET /api/validate-cpf` - Validação de CPF
- `GET /api/cep` - Consulta de CEP
- `GET /api/translate` - Tradução de texto
- `GET /api/currency` - Conversão de moedas

### Autenticação
- `POST /api/auth/login` - Login
- `POST /api/auth/register` - Registro
- `POST /api/auth/refresh` - Renovar token

### Protegidos (requer JWT)
- `GET /api/user/profile` - Perfil do usuário
- `PUT /api/user/profile` - Atualizar perfil
- `GET /api/user/stats` - Estatísticas do usuário
- `GET /api/data/analytics` - Analytics da API
- `POST /api/data/upload` - Upload de dados

### Documentação
- `GET /api/docs` - Documentação interativa
- `GET /api/docs.json` - Especificação OpenAPI
- `GET /dashboard` - Dashboard para testes

## 🔧 Exemplos de Uso

### Gerar números aleatórios
```bash
curl "http://localhost:8000/api/random?min=1&max=100&count=5"
```

### Consultar previsão do tempo
```bash
curl "http://localhost:8000/api/weather?city=São Paulo"
```

### Validar CPF
```bash
curl "http://localhost:8000/api/validate-cpf?cpf=12345678909"
```

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@api.com","password":"123456"}'
```

### Usar endpoint protegido
```bash
curl -H "Authorization: Bearer SEU_TOKEN_JWT" \
  http://localhost:8000/api/user/profile
```

## 🎨 Dashboard

Acesse `http://localhost:8000/dashboard` para uma interface web interativa onde você pode testar todos os endpoints da API.

## 📊 Monitoramento

A API inclui:
- Rate limiting (100 requests/hora por IP)
- Logs de todas as requisições
- Métricas de performance
- Tratamento de erros estruturado

## 🔒 Segurança

- Autenticação JWT
- Rate limiting
- Validação de entrada
- CORS configurado
- Headers de segurança

## 🚀 Deploy

### Docker (recomendado)
```bash
docker build -t impressive-api .
docker run -p 8000:8000 impressive-api
```

### Produção
1. Configure um servidor web (Apache/Nginx)
2. Configure as variáveis de ambiente
3. Configure Redis para cache
4. Configure logs

## 📝 Licença

MIT License - veja o arquivo LICENSE para detalhes.

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

---

**Desenvolvido com ❤️ para impressionar!** 