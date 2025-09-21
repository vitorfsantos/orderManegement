# Módulo Dashboard

Este módulo fornece um dashboard funcional e componentizado para o sistema de gestão de pedidos.

## Estrutura

```
Dashboard/
├── Controllers/
│   └── DashboardController.php
├── Services/
│   └── DashboardService.php
├── routes.php
├── DashboardServiceProvider.php
└── README.md
```

## Funcionalidades

### DashboardController
- Controlador principal que gerencia a exibição do dashboard
- Utiliza o DashboardService para obter dados

### DashboardService
- Busca estatísticas em tempo real:
  - Total de produtos
  - Pedidos do dia
  - Receita do dia
  - Usuários ativos
- Calcula crescimento percentual comparado ao período anterior
- Fornece dados para:
  - Pedidos recentes
  - Receita mensal (últimos 12 meses)
  - Produtos mais vendidos

## Componentes

Os componentes estão localizados em `resources/views/Dashboard/components/`:

- `stats-card.blade.php` - Card de estatísticas reutilizável
- `recent-orders.blade.php` - Lista de pedidos recentes
- `quick-actions.blade.php` - Ações rápidas do sistema
- `revenue-chart.blade.php` - Gráfico de receita mensal
- `top-products.blade.php` - Produtos mais vendidos

## Rotas

- `GET /dashboard` - Exibe o dashboard principal

## Dependências

- Módulo Orders (para dados de pedidos)
- Módulo Products (para dados de produtos)
- Módulo Users (para dados de usuários)

## Uso

O dashboard é automaticamente carregado quando o usuário acessa `/dashboard` e está autenticado.
