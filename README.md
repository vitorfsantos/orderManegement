# Orders Management System

Sistema de gestão de pedidos desenvolvido em Laravel com arquitetura modular, Livewire e Tailwind CSS.

## 📋 Índice

- [Visão Geral](#visão-geral)
- [Tecnologias](#tecnologias)
- [Funcionalidades](#funcionalidades)
- [Arquitetura](#arquitetura)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Módulos](#módulos)
- [API e Rotas](#api-e-rotas)
- [Banco de Dados](#banco-de-dados)
- [Desenvolvimento](#desenvolvimento)
- [Testes](#testes)
- [Deploy](#deploy)
- [Contribuição](#contribuição)

## 🎯 Visão Geral

O Orders Management System é uma aplicação web completa para gestão de pedidos, produtos e usuários. O sistema foi desenvolvido seguindo princípios de arquitetura modular, com separação clara de responsabilidades e código bem estruturado.

### Características Principais

- **Arquitetura Modular**: Sistema organizado em módulos independentes
- **Interface Moderna**: UI responsiva com Tailwind CSS e Livewire
- **Autenticação Completa**: Sistema de login com diferentes perfis de usuário
- **Gestão de Produtos**: CRUD completo com controle de estoque
- **Carrinho de Compras**: Sistema de carrinho com sessão
- **Gestão de Pedidos**: Processo completo de pedidos com status
- **Dashboard**: Painel administrativo com estatísticas
- **UUID**: Identificadores únicos universais para todas as entidades
- **Soft Deletes**: Exclusão lógica de registros

## 🛠 Tecnologias

### Backend
- **Laravel 12.x** - Framework PHP
- **Livewire 3.x** - Full-stack framework para Laravel
- **Livewire Flux** - Componentes UI para Livewire
- **Livewire Volt** - Functional API para Livewire
- **SQLite** - Banco de dados (desenvolvimento)
- **UUID** - Identificadores únicos

### Frontend
- **Tailwind CSS 4.x** - Framework CSS
- **Vite** - Build tool
- **Alpine.js** - Framework JavaScript leve
- **Heroicons** - Ícones SVG

### Ferramentas de Desenvolvimento
- **Laravel Pint** - Code style fixer
- **Laravel Pail** - Log viewer
- **PHPUnit** - Testes automatizados
- **Faker** - Geração de dados fake

## ✨ Funcionalidades

### 🔐 Autenticação e Usuários
- Login/Logout com sessão
- Registro de usuários
- Perfis de usuário (Admin/Customer)
- Verificação de email
- Recuperação de senha
- Configurações de perfil

### 📦 Gestão de Produtos
- Listagem de produtos com filtros
- Visualização detalhada de produtos
- CRUD completo (Admin)
- Controle de estoque
- Status ativo/inativo
- Slug único para URLs amigáveis

### 🛒 Carrinho de Compras
- Adicionar produtos ao carrinho
- Remover produtos do carrinho
- Atualizar quantidades
- Persistência em sessão
- Contador de itens no header

### 📋 Gestão de Pedidos
- Criação de pedidos a partir do carrinho
- Listagem de pedidos por usuário
- Visualização detalhada de pedidos
- Atualização de status (Admin)
- Código único para cada pedido
- Email de confirmação

### 📊 Dashboard
- Estatísticas em tempo real
- Gráficos de receita
- Pedidos recentes
- Produtos mais vendidos
- Ações rápidas

## 🏗 Arquitetura

### Arquitetura Modular

O sistema está organizado em módulos independentes, cada um com sua própria estrutura:

```
app/Modules/
├── Auth/          # Autenticação
├── Users/         # Gestão de usuários
├── Products/      # Gestão de produtos
├── Orders/        # Gestão de pedidos
├── Cart/          # Carrinho de compras
└── Dashboard/     # Painel administrativo
```

### Padrões de Design

- **Service Layer**: Lógica de negócio em services
- **Repository Pattern**: Acesso a dados abstraído
- **Observer Pattern**: Eventos de modelo
- **Factory Pattern**: Criação de objetos
- **Strategy Pattern**: Diferentes estratégias de processamento

### Princípios SOLID

- **Single Responsibility**: Cada classe tem uma responsabilidade
- **Open/Closed**: Aberto para extensão, fechado para modificação
- **Liskov Substitution**: Substituição de implementações
- **Interface Segregation**: Interfaces específicas
- **Dependency Inversion**: Dependências abstraídas

## 🚀 Instalação

### Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js 18+ e NPM
- SQLite (ou MySQL/PostgreSQL)

### Passos de Instalação

1. **Clone o repositório**
```bash
git clone <repository-url>
cd ordersManegement
```

2. **Instale as dependências PHP**
```bash
composer install
```

3. **Instale as dependências Node.js**
```bash
npm install
```

4. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure o banco de dados**
```bash
# Para SQLite (padrão)
touch database/database.sqlite

# Para MySQL/PostgreSQL, configure no .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=orders_management
# DB_USERNAME=root
# DB_PASSWORD=
```

6. **Execute as migrações**
```bash
php artisan migrate
```

7. **Execute os seeders (opcional)**
```bash
php artisan db:seed
```

8. **Compile os assets**
```bash
npm run build
```

9. **Inicie o servidor**
```bash
php artisan serve
```

## ⚙️ Configuração

### Variáveis de Ambiente

Principais configurações no arquivo `.env`:

```env
APP_NAME="Orders Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Configuração de Email

Para desenvolvimento, o sistema está configurado para usar Mailpit:

```bash
# Instalar Mailpit
curl -L https://github.com/axllent/mailpit/releases/latest/download/mailpit-linux-amd64.tar.gz | tar -xz
sudo mv mailpit /usr/local/bin/

# Iniciar Mailpit
mailpit
```

Acesse a interface em: http://localhost:8025

## 📁 Estrutura do Projeto

```
ordersManegement/
├── app/
│   ├── Http/Controllers/          # Controllers principais
│   ├── Livewire/                  # Componentes Livewire
│   ├── Models/                    # Modelos base
│   ├── Modules/                   # Módulos do sistema
│   │   ├── Auth/                  # Autenticação
│   │   ├── Cart/                  # Carrinho
│   │   ├── Dashboard/             # Dashboard
│   │   ├── Orders/                # Pedidos
│   │   ├── Products/              # Produtos
│   │   └── Users/                 # Usuários
│   └── Providers/                 # Service Providers
├── bootstrap/                     # Arquivos de inicialização
├── config/                        # Configurações
├── database/
│   ├── factories/                 # Factories para testes
│   ├── migrations/                # Migrações do banco
│   └── seeders/                   # Seeders
├── docs/                          # Documentação
├── public/                        # Arquivos públicos
├── resources/
│   ├── css/                       # Estilos CSS
│   ├── js/                        # JavaScript
│   └── views/                     # Views Blade
├── routes/                        # Definição de rotas
├── storage/                       # Arquivos de armazenamento
├── tests/                         # Testes automatizados
└── vendor/                        # Dependências Composer
```

## 🔧 Módulos

### Auth Module
**Localização**: `app/Modules/Auth/`

Responsável pela autenticação de usuários:
- Login/Logout
- Registro
- Verificação de email
- Recuperação de senha

**Controllers**:
- `ProcessLoginController`
- `ProcessLogoutController`
- `ShowLoginController`

**Services**:
- `ProcessLoginService`
- `ProcessLogoutService`
- `ShowLoginService`

### Users Module
**Localização**: `app/Modules/Users/`

Gestão completa de usuários:
- CRUD de usuários
- Perfis (Admin/Customer)
- Middleware de autorização

**Controllers**:
- `CreateUserController`
- `DeleteUserController`
- `EditUserController`
- `ListUsersController`

**Services**:
- `CreateUserService`
- `DeleteUserService`
- `EditUserService`
- `ListUsersService`

### Products Module
**Localização**: `app/Modules/Products/`

Gestão de produtos:
- CRUD completo
- Controle de estoque
- Status ativo/inativo
- Slug único

**Controllers**:
- `CreateProductController`
- `DeleteProductController`
- `EditProductController`
- `ListProductsController`
- `ShowProductController`

**Services**:
- `CreateProductService`
- `DeleteProductService`
- `EditProductService`
- `ListProductsService`

### Orders Module
**Localização**: `app/Modules/Orders/`

Gestão de pedidos:
- Criação de pedidos
- Listagem e visualização
- Atualização de status
- Email de confirmação

**Controllers**:
- `FinishOrderController`
- `GetOrderDetailsController`
- `ListOrdersController`
- `ShowOrderController`
- `UpdateOrderStatusController`

**Services**:
- `FinishOrderService`
- `ListOrdersService`
- `ShowOrderService`
- `UpdateOrderStatusService`

### Cart Module
**Localização**: `app/Modules/Cart/`

Carrinho de compras:
- Adicionar/remover produtos
- Atualizar quantidades
- Persistência em sessão

**Controllers**:
- `CartController`

**Services**:
- `AddToCartService`
- `GetCartCountService`
- `ListCartService`
- `RemoveFromCartService`

### Dashboard Module
**Localização**: `app/Modules/Dashboard/`

Painel administrativo:
- Estatísticas em tempo real
- Gráficos e métricas
- Ações rápidas

**Controllers**:
- `DashboardController`

**Services**:
- `DashboardService`

## 🛣 API e Rotas

### Rotas Principais

```php
// Página inicial
GET / → Redireciona baseado no perfil do usuário

// Autenticação
GET /login → Formulário de login
POST /login → Processar login
POST /logout → Logout

// Produtos
GET /products → Lista de produtos
GET /products/{slug} → Detalhes do produto

// Carrinho
GET /cart → Visualizar carrinho
POST /cart/add → Adicionar ao carrinho
POST /cart/remove → Remover do carrinho

// Pedidos
GET /orders → Lista de pedidos do usuário
GET /orders/{order} → Detalhes do pedido
POST /orders → Finalizar pedido

// Dashboard (Admin)
GET /dashboard → Dashboard administrativo

// Configurações
GET /settings/profile → Configurações de perfil
GET /settings/password → Alterar senha
GET /settings/appearance → Configurações de aparência
```

### Middleware

- `auth` - Usuário autenticado
- `admin` - Usuário administrador
- `web` - Sessão web

## 🗄 Banco de Dados

### Tabelas Principais

#### users
```sql
- id (UUID, Primary Key)
- name (String)
- email (String, Unique)
- email_verified_at (Timestamp)
- password (String, Hashed)
- role (Enum: admin, customer)
- remember_token (String)
- created_at (Timestamp)
- updated_at (Timestamp)
- deleted_at (Timestamp) -- Soft Delete
```

#### products
```sql
- id (UUID, Primary Key)
- name (String)
- slug (String, Unique)
- price (Decimal 10,2)
- stock (Integer)
- active (Boolean)
- created_at (Timestamp)
- updated_at (Timestamp)
- deleted_at (Timestamp) -- Soft Delete
```

#### orders
```sql
- id (UUID, Primary Key)
- code (String, Unique) -- UUID gerado automaticamente
- user_id (UUID, Foreign Key)
- total (Decimal 10,2)
- status (Enum: pending, paid, cancelled)
- created_at (Timestamp)
- updated_at (Timestamp)
- deleted_at (Timestamp) -- Soft Delete
```

#### order_items
```sql
- id (UUID, Primary Key)
- order_id (UUID, Foreign Key)
- product_id (UUID, Foreign Key)
- quantity (Integer)
- unit_price (Decimal 10,2)
- created_at (Timestamp)
- updated_at (Timestamp)
- deleted_at (Timestamp) -- Soft Delete
```

### Relacionamentos

- `User` hasMany `Order`
- `Order` belongsTo `User`
- `Order` hasMany `OrderItem`
- `OrderItem` belongsTo `Order`
- `OrderItem` belongsTo `Product`
- `Product` hasMany `OrderItem`

## 💻 Desenvolvimento

### Comandos Úteis

```bash
# Desenvolvimento completo
composer run dev

# Servidor Laravel
php artisan serve

# Compilar assets
npm run dev
npm run build

# Executar testes
composer run test

# Code style
./vendor/bin/pint

# Logs em tempo real
php artisan pail

# Queue worker
php artisan queue:work
```

### Estrutura de Desenvolvimento

1. **Controllers**: Apenas direcionamento, sem lógica de negócio
2. **Services**: Toda lógica de negócio
3. **Requests**: Validação de dados
4. **Models**: Relacionamentos e scopes
5. **Views**: Interface do usuário

### Adicionando Novos Módulos

1. Criar estrutura de diretórios
2. Implementar controllers, services, models
3. Adicionar rotas
4. Registrar no `ModuleServiceProvider`
5. Criar views e componentes

## 🧪 Testes

### Executar Testes

```bash
# Todos os testes
php artisan test

# Testes específicos
php artisan test --filter=OrderTest

# Com coverage
php artisan test --coverage
```

### Estrutura de Testes

```
tests/
├── Feature/           # Testes de integração
│   ├── Orders/        # Testes de pedidos
│   └── Settings/      # Testes de configurações
└── Unit/              # Testes unitários
```

### Exemplos de Testes

```php
// Teste de criação de pedido
public function test_customer_can_create_order()
{
    $user = User::factory()->create(['role' => 'customer']);
    $product = Product::factory()->create(['stock' => 10]);
    
    $this->actingAs($user);
    
    $response = $this->post('/cart/add', [
        'product_id' => $product->id,
        'quantity' => 2
    ]);
    
    $response->assertJson(['success' => true]);
}
```

## 🚀 Deploy

### Produção

1. **Configurar ambiente**
```bash
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
# ... outras configurações
```

2. **Otimizar aplicação**
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

3. **Configurar servidor web**
- Apache/Nginx
- SSL/HTTPS
- Configuração de domínio

### Docker (Opcional)

```dockerfile
FROM php:8.2-fpm
# ... configuração do container
```

## 🤝 Contribuição

### Como Contribuir

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

### Padrões de Código

- Seguir PSR-12
- Usar Laravel Pint para formatação
- Escrever testes para novas funcionalidades
- Documentar mudanças importantes
- Manter compatibilidade com versões suportadas

### Issues

- Use templates fornecidos
- Seja específico e claro
- Inclua passos para reproduzir
- Adicione screenshots quando relevante

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 📞 Suporte

Para suporte e dúvidas:

- Abra uma [issue](https://github.com/username/ordersManegement/issues)
- Consulte a [documentação](docs/)
- Entre em contato via email

---

**Desenvolvido com ❤️ usando Laravel e Livewire**
