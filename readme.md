# 💰 Finanças do Casamento

Sistema de controle financeiro pessoal, desenvolvido em PHP puro, para gerenciar as finanças compartilhadas de um casal.

## Funcionalidades

- Cadastro e login de usuários (com senha protegida via hash)
- CRUD completo de transações (criar, listar, editar, excluir)
- Categorias de transações
- Vínculo de cada transação a quem a lançou
- Filtros por período, categoria e responsável
- Dashboard com resumo geral e gráfico de gastos por categoria
- Metas de orçamento mensal por categoria, com comparativo de gasto

## Tecnologias

- PHP puro (sem frameworks)
- MySQL
- PDO com prepared statements (proteção contra SQL Injection)
- Chart.js (via CDN) para gráficos

## Estrutura do projeto

financas/
├── public/ → arquivos acessíveis pelo navegador
│ ├── index.php
│ ├── login.php
│ ├── cadastro.php
│ ├── logout.php
│ ├── categorias.php
│ ├── dashboard.php
│ ├── metas.php
│ ├── editar.php
│ ├── excluir.php
│ └── style.css
├── src/ → funções PHP reutilizáveis
│ ├── funcoes.php
│ ├── transacoes.php
│ └── orcamento.php
├── config/ → configuração (não versionado, contém senha)
│ └── database.php
└── schema.sql → estrutura do banco de dados


## Como rodar localmente

1. Clone o repositório
2. Crie o banco de dados executando o `schema.sql`
3. Configure `config/database.php` com suas credenciais do MySQL (veja o exemplo abaixo)
4. Rode o servidor embutido do PHP:
```bash
   php -S localhost:8000 -t public
```
5. Acesse `http://localhost:8000/cadastro.php` para criar o primeiro usuário

### Exemplo de `config/database.php`

```php
<?php
$host = "localhost";
$dbname = "financas_casamento";
$user = "seu_usuario";
$password = "sua_senha";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
```

## Sobre o projeto

Este sistema foi desenvolvido como projeto de estudo, dia após dia, aprendendo PHP e MySQL do zero — desde a configuração do ambiente até funcionalidades completas de autenticação, relatórios e orçamento.