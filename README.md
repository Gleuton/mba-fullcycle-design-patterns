# FullCycle MBA Design Patterns

Projeto didático da matéria de design patterns do curso MBA FullCycle.

## Executando os testes

Os testes devem ser executados dentro da imagem Docker. Para isso, siga os passos abaixo:

1. Inicie os containers Docker:

```bash
docker compose up -d
```

2. Execute os testes diretamente:

```bash
docker compose exec mba-patters-app vendor/bin/phpunit
```

Para executar um teste específico:

```bash
docker compose exec mba-patters-app vendor/bin/phpunit test/AppTest/GenerateInvoicesTest.php
```

## Estrutura do projeto

- `src/`: Código fonte da aplicação
- `test/`: Testes automatizados
- `bin/`: Scripts utilitários
- `.docker/`: Configurações do Docker

