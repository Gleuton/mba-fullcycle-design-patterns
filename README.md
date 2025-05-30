# FullCycle MBA Design Patterns

Projeto didático da matéria de design patterns do curso MBA FullCycle, demonstrando a implementação prática de padrões de projeto em PHP.

## 📋 Pré-requisitos

- Docker
- Docker Compose
- Git

## 🚀 Instalação

1. Clone o repositório:
```bash
git clone [url-do-repositorio]
cd FullCycleMbaDesignPatters
```

2. Inicie os containers Docker:
```bash
docker compose up -d
```

3. Instale as dependências:
```bash
docker compose exec mba-patters-app composer install
```

## 🧪 Executando os testes

Os testes são executados dentro do container Docker:

```bash
# Executar todos os testes
docker compose exec mba-patters-app vendor/bin/phpunit

# Executar um teste específico
docker compose exec mba-patters-app vendor/bin/phpunit test/AppTest/GenerateInvoicesTest.php
```

## 🛠️ Tecnologias utilizadas

- PHP 8+
- Mezzio Framework
- Doctrine ORM
- PHPUnit para testes
- Docker
- Nginx

