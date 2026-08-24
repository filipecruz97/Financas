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