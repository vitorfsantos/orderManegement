# Guia: Catálogo com Pedidos - Laravel + Livewire (Arquitetura Modular)

## Índice
1. [Configuração Inicial](#configuração-inicial)
2. [Arquitetura Modular](#arquitetura-modular)
3. [Backend - Autenticação e Perfis](#backend---autenticação-e-perfis)
4. [Backend - Modelos e Migrations](#backend---modelos-e-migrations)
5. [Backend - Regras de Negócio](#backend---regras-de-negócio)
6. [Frontend - Listagem de Produtos](#frontend---listagem-de-produtos)
7. [Frontend - Detalhe do Produto](#frontend---detalhe-do-produto)
8. [Frontend - Carrinho e Pedidos](#frontend---carrinho-e-pedidos)
9. [Segurança - Policies](#segurança---policies)
10. [Extras - Cache, Jobs e Testes](#extras---cache-jobs-e-testes)

---

## Configuração Inicial

### 1. Instalar Laravel Breeze com Inertia.js (SPA)
```bash
composer require laravel/breeze --dev
php artisan breeze:install vue --ssr
npm install && npm run build
php artisan migrate
```

### 2. Configurar Inertia.js para SPA Modular
```bash
# Instalar dependências adicionais
npm install @inertiajs/vue3 @vitejs/plugin-vue
npm install vue@next @vue/compiler-sfc
npm install @headlessui/vue @heroicons/vue
```

### 3. Configurar UUID e Soft Deletes
Instalar pacote para UUID:
```bash
composer require ramsey/uuid
```

### 4. Configurar Perfis de Usuário
Adicionar campo `role` na migration de usuários:

```bash
php artisan make:migration add_role_to_users_table
```

```php
// database/migrations/xxxx_add_role_to_users_table.php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['admin', 'customer'])->default('customer');
        $table->softDeletes();
    });
}
```

---

## Arquitetura SPA Modular

### 1. Estrutura de Views Modulares
```bash
# Criar estrutura de views organizadas por módulo
mkdir -p resources/js/Pages/{Users,Products,Orders,Cart}
mkdir -p resources/js/Components/{Users,Products,Orders,Cart}
mkdir -p resources/js/Layouts
mkdir -p resources/js/Shared
```

### 2. Configuração do Vite para Módulos
```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            '@Users': '/resources/js/Pages/Users',
            '@Products': '/resources/js/Pages/Products',
            '@Orders': '/resources/js/Pages/Orders',
            '@Cart': '/resources/js/Pages/Cart',
        },
    },
});
```

### 3. App.js Principal com Inertia
```javascript
// resources/js/app.js
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

// Importar layouts
import MainLayout from '@/Layouts/MainLayout.vue'
import AuthLayout from '@/Layouts/AuthLayout.vue'

createInertiaApp({
    title: (title) => `${title} - Orders Management`,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        )
        page.then((module) => {
            module.default.layout = module.default.layout || MainLayout
        })
        return page
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    },
})
```

### 4. Layout Principal Modular
```vue
<!-- resources/js/Layouts/MainLayout.vue -->
<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')" class="text-xl font-bold">
                                Orders Management
                            </Link>
                        </div>
                        
                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <NavLink :href="route('products.index')" :active="$page.url.startsWith('/products')">
                                Products
                            </NavLink>
                            <NavLink :href="route('orders.index')" :active="$page.url.startsWith('/orders')">
                                Orders
                            </NavLink>
                            <NavLink :href="route('cart.index')" :active="$page.url.startsWith('/cart')">
                                Cart
                            </NavLink>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <CartCounter />
                        <UserMenu />
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import NavLink from '@/Shared/NavLink.vue'
import CartCounter from '@/Shared/CartCounter.vue'
import UserMenu from '@/Shared/UserMenu.vue'
</script>
```

---

## Arquitetura Modular

### 1. Estrutura de Módulos
Criar a estrutura de módulos:

```bash
mkdir -p app/Modules/{Users,Products,Orders,Cart}
```

Para cada módulo, criar a estrutura:
```bash
# Exemplo para módulo Users
mkdir -p app/Modules/Users/{Controllers,Models,Services,Requests,Policies,Observers,Jobs,Mail,Livewire}
touch app/Modules/Users/routes.php
```

### 2. Service Provider para Módulos
```bash
php artisan make:provider ModuleServiceProvider
```

```php
// app/Providers/ModuleServiceProvider.php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModuleServiceProvider extends ServiceProvider
{
    protected $modules = [
        'Users',
        'Products', 
        'Orders',
        'Cart'
    ];

    public function boot()
    {
        foreach ($this->modules as $module) {
            $this->loadModuleRoutes($module);
            $this->loadModuleViews($module);
        }
    }

    protected function loadModuleRoutes($module)
    {
        $routesPath = app_path("Modules/{$module}/routes.php");
        if (file_exists($routesPath)) {
            Route::prefix('api')
                ->middleware('api')
                ->group($routesPath);
                
            Route::middleware('web')
                ->group($routesPath);
        }
    }

    protected function loadModuleViews($module)
    {
        $viewsPath = app_path("Modules/{$module}/Views");
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, strtolower($module));
        }
    }
}
```

### 3. Registrar Service Provider
```php
// config/app.php
'providers' => [
    // ...
    App\Providers\ModuleServiceProvider::class,
],
```

### 4. Base Model com UUID e Soft Deletes
```php
// app/Models/BaseModel.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $dates = ['deleted_at'];
}
```

---

## Backend - Autenticação e Perfis

### 1. Módulo Users - Model User
```php
// app/Modules/Users/Models/User.php
<?php

namespace App\Modules\Users\Models;

use App\Models\BaseModel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function orders()
    {
        return $this->hasMany(\App\Modules\Orders\Models\Order::class);
    }
}
```

### 2. Módulo Users - Middleware Admin
```bash
php artisan make:middleware AdminMiddleware
```

```php
// app/Modules/Users/Middleware/AdminMiddleware.php
<?php

namespace App\Modules\Users\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Access denied');
        }
        
        return $next($request);
    }
}
```

### 3. Módulo Users - Controllers Granulares
```bash
# Criar controllers específicos para cada funcionalidade
php artisan make:controller CreateUserController
php artisan make:controller UpdateUserController
php artisan make:controller DeleteUserController
php artisan make:controller ShowUserController
php artisan make:controller ListUsersController
```

```php
// app/Modules/Users/Controllers/CreateUserController.php
<?php

namespace App\Modules\Users\Controllers;

use App\Modules\Users\Services\CreateUserService;
use App\Modules\Users\Requests\CreateUserRequest;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class CreateUserController extends Controller
{
    public function __construct(
        private CreateUserService $createUserService
    ) {}

    public function show(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(CreateUserRequest $request): RedirectResponse
    {
        $user = $this->createUserService->execute($request->validated());
        
        return redirect()->route('users.show', $user)
            ->with('success', 'User created successfully');
    }
}
```

```php
// app/Modules/Users/Controllers/UpdateUserController.php
<?php

namespace App\Modules\Users\Controllers;

use App\Modules\Users\Services\UpdateUserService;
use App\Modules\Users\Requests\UpdateUserRequest;
use App\Modules\Users\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class UpdateUserController extends Controller
{
    public function __construct(
        private UpdateUserService $updateUserService
    ) {}

    public function show(User $user): Response
    {
        return Inertia::render('Users/Edit', [
            'user' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->updateUserService->execute($user, $request->validated());
        
        return redirect()->route('users.show', $user)
            ->with('success', 'User updated successfully');
    }
}
```

### 4. Módulo Users - Services Granulares
```bash
# Criar services específicos para cada funcionalidade
php artisan make:service CreateUserService
php artisan make:service UpdateUserService
php artisan make:service DeleteUserService
php artisan make:service ShowUserService
php artisan make:service ListUsersService
```

```php
// app/Modules/Users/Services/CreateUserService.php
<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserService
{
    public function execute(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        
        return User::create($data);
    }
}
```

```php
// app/Modules/Users/Services/UpdateUserService.php
<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserService
{
    public function execute(User $user, array $data): void
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        $user->update($data);
    }
}
```

### 5. Módulo Users - Routes
```php
// app/Modules/Users/routes.php
<?php

use App\Modules\Users\Controllers\{
    CreateUserController,
    UpdateUserController,
    DeleteUserController,
    ShowUserController,
    ListUsersController
};
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/users', [ListUsersController::class, 'index'])->name('users.index');
Route::get('/users/{user}', [ShowUserController::class, 'show'])->name('users.show');

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users/create', [CreateUserController::class, 'show'])->name('users.create');
    Route::post('/users', [CreateUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UpdateUserController::class, 'show'])->name('users.edit');
    Route::patch('/users/{user}', [UpdateUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [DeleteUserController::class, 'destroy'])->name('users.destroy');
});
```

---

## Backend - Modelos e Migrations

### 1. Módulo Products - Model Product
```bash
php artisan make:model Product -m
```

```php
// database/migrations/xxxx_create_products_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
```

```php
// app/Modules/Products/Models/Product.php
<?php

namespace App\Modules\Products\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends BaseModel
{
    protected $fillable = [
        'name', 'slug', 'price', 'stock', 'active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(\App\Modules\Orders\Models\OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
```

### 2. Módulo Orders - Model Order
```bash
php artisan make:model Order -m
```

```php
// database/migrations/xxxx_create_orders_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
```

```php
// app/Modules/Orders/Models/Order.php
<?php

namespace App\Modules\Orders\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends BaseModel
{
    protected $fillable = [
        'code', 'user_id', 'total', 'status'
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            $order->code = Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Users\Models\User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
```

### 3. Módulo Orders - Model OrderItem
```bash
php artisan make:model OrderItem -m
```

```php
// database/migrations/xxxx_create_order_items_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('order_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
```

```php
// app/Modules/Orders/Models/OrderItem.php
<?php

namespace App\Modules\Orders\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends BaseModel
{
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Products\Models\Product::class);
    }
}
```

---

## Backend - Regras de Negócio

### 1. Módulo Products - Form Requests
```bash
php artisan make:request StoreProductRequest
php artisan make:request UpdateProductRequest
```

```php
// app/Modules/Products/Requests/StoreProductRequest.php
<?php

namespace App\Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'active' => 'boolean',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
}
```

```php
// app/Modules/Products/Requests/UpdateProductRequest.php
<?php

namespace App\Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    public function rules()
    {
        $productId = $this->route('product');
        
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $productId,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'active' => 'boolean',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
}
```

### 2. Módulo Products - Stock Service
```bash
php artisan make:service StockService
```

```php
// app/Modules/Products/Services/StockService.php
<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;
use Exception;

class StockService
{
    public function checkAvailability(string $productId, int $quantity): bool
    {
        $product = Product::findOrFail($productId);
        return $product->stock >= $quantity;
    }

    public function reduceStock(string $productId, int $quantity): void
    {
        $product = Product::findOrFail($productId);
        
        if ($product->stock < $quantity) {
            throw new Exception('Insufficient stock');
        }
        
        $product->decrement('stock', $quantity);
    }

    public function restoreStock(string $productId, int $quantity): void
    {
        $product = Product::findOrFail($productId);
        $product->increment('stock', $quantity);
    }
}
```

### 3. Módulo Products - Observer para Slug único
```bash
php artisan make:observer ProductObserver --model=Product
```

```php
// app/Modules/Products/Observers/ProductObserver.php
<?php

namespace App\Modules\Products\Observers;

use App\Modules\Products\Models\Product;
use Illuminate\Support\Str;

class ProductObserver
{
    public function creating(Product $product)
    {
        if (empty($product->slug)) {
            $product->slug = Str::slug($product->name);
        }
        
        // Garantir slug único
        $originalSlug = $product->slug;
        $counter = 1;
        
        while (Product::where('slug', $product->slug)->exists()) {
            $product->slug = $originalSlug . '-' . $counter;
            $counter++;
        }
    }
}
```

### 4. Módulo Products - Controllers Granulares
```bash
# Criar controllers específicos para cada funcionalidade
php artisan make:controller CreateProductController
php artisan make:controller UpdateProductController
php artisan make:controller DeleteProductController
php artisan make:controller ShowProductController
php artisan make:controller ListProductsController
```

```php
// app/Modules/Products/Controllers/CreateProductController.php
<?php

namespace App\Modules\Products\Controllers;

use App\Modules\Products\Services\CreateProductService;
use App\Modules\Products\Requests\StoreProductRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CreateProductController extends Controller
{
    public function __construct(
        private CreateProductService $createProductService
    ) {}

    public function show(): View
    {
        return view('products::create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = $this->createProductService->execute($request->validated());
        
        return redirect()->route('products.show', $product->slug)
            ->with('success', 'Product created successfully');
    }
}
```

```php
// app/Modules/Products/Controllers/ShowProductController.php
<?php

namespace App\Modules\Products\Controllers;

use App\Modules\Products\Models\Product;
use App\Modules\Products\Services\ShowProductService;
use Illuminate\View\View;

class ShowProductController extends Controller
{
    public function __construct(
        private ShowProductService $showProductService
    ) {}

    public function show(string $slug): View
    {
        $product = $this->showProductService->execute($slug);
        
        return view('products::show', compact('product'));
    }
}
```

### 5. Módulo Products - Services Granulares
```bash
# Criar services específicos para cada funcionalidade
php artisan make:service CreateProductService
php artisan make:service UpdateProductService
php artisan make:service DeleteProductService
php artisan make:service ShowProductService
php artisan make:service ListProductsService
```

```php
// app/Modules/Products/Services/CreateProductService.php
<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;
use Illuminate\Support\Str;

class CreateProductService
{
    public function execute(array $data): Product
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Garantir slug único
        $originalSlug = $data['slug'];
        $counter = 1;
        
        while (Product::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return Product::create($data);
    }
}
```

```php
// app/Modules/Products/Services/ShowProductService.php
<?php

namespace App\Modules\Products\Services;

use App\Modules\Products\Models\Product;

class ShowProductService
{
    public function execute(string $slug): Product
    {
        return Product::where('slug', $slug)->firstOrFail();
    }
}
```

### 6. Módulo Products - Routes
```php
// app/Modules/Products/routes.php
<?php

use App\Modules\Products\Controllers\{
    CreateProductController,
    UpdateProductController,
    DeleteProductController,
    ShowProductController,
    ListProductsController
};
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/products', [ListProductsController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ShowProductController::class, 'show'])->name('products.show');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/products/create', [CreateProductController::class, 'show'])->name('products.create');
    Route::post('/products', [CreateProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [UpdateProductController::class, 'show'])->name('products.edit');
    Route::patch('/products/{product}', [UpdateProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [DeleteProductController::class, 'destroy'])->name('products.destroy');
});
```

---

## Frontend SPA Modular

### 1. Módulo Users - Views Vue.js
```vue
<!-- resources/js/Pages/Users/Index.vue -->
<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Users</h1>
                        <Link 
                            :href="route('users.create')" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                        >
                            Create User
                        </Link>
                    </div>
                    
                    <UsersTable :users="users" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import UsersTable from '@/Components/Users/UsersTable.vue'

defineProps({
    users: Object
})
</script>
```

```vue
<!-- resources/js/Pages/Users/Create.vue -->
<template>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">Create User</h1>
                    
                    <UserForm 
                        :form="form"
                        @submit="submit"
                        submit-text="Create User"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import UserForm from '@/Components/Users/UserForm.vue'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'customer'
})

const submit = () => {
    form.post(route('users.store'))
}
</script>
```

### 2. Módulo Products - Views Vue.js
```vue
<!-- resources/js/Pages/Products/Index.vue -->
<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Products</h1>
                        <Link 
                            v-if="$page.props.auth.user?.isAdmin"
                            :href="route('admin.products.create')" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                        >
                            Create Product
                        </Link>
                    </div>
                    
                    <ProductsFilters 
                        :search="search"
                        :active-only="activeOnly"
                        @update:search="search = $event"
                        @update:active-only="activeOnly = $event"
                    />
                    
                    <ProductsGrid :products="products" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import ProductsFilters from '@/Components/Products/ProductsFilters.vue'
import ProductsGrid from '@/Components/Products/ProductsGrid.vue'

defineProps({
    products: Object
})

const search = ref('')
const activeOnly = ref(true)
</script>
```

```vue
<!-- resources/js/Pages/Products/Show.vue -->
<template>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h1 class="text-3xl font-bold mb-4">{{ product.name }}</h1>
                            <p class="text-2xl text-green-600 font-semibold mb-4">
                                $ {{ product.price }}
                            </p>
                            <p class="text-gray-600 mb-6">
                                Stock: {{ product.stock }} units
                            </p>
                            
                            <AddToCartForm 
                                v-if="product.active && product.stock > 0"
                                :product="product"
                                @added="onItemAdded"
                            />
                            
                            <div v-else class="bg-gray-100 p-4 rounded-lg">
                                <p class="text-gray-600">
                                    {{ !product.active ? 'Product unavailable' : 'Out of stock' }}
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <ProductImage :product="product" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import AddToCartForm from '@/Components/Products/AddToCartForm.vue'
import ProductImage from '@/Components/Products/ProductImage.vue'

defineProps({
    product: Object
})

const onItemAdded = () => {
    // Atualizar contador do carrinho
    router.reload({ only: ['cartCount'] })
}
</script>
```

### 3. Módulo Orders - Views Vue.js
```vue
<!-- resources/js/Pages/Orders/Index.vue -->
<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">My Orders</h1>
                    
                    <OrdersList :orders="orders" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import OrdersList from '@/Components/Orders/OrdersList.vue'

defineProps({
    orders: Object
})
</script>
```

### 4. Módulo Cart - Views Vue.js
```vue
<!-- resources/js/Pages/Cart/Index.vue -->
<template>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>
                    
                    <div v-if="cartItems.length > 0">
                        <CartItemsList 
                            :items="cartItems"
                            @updated="onCartUpdated"
                            @removed="onItemRemoved"
                        />
                        
                        <div class="mt-6 pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold">
                                    Total: $ {{ total }}
                                </span>
                                <Link 
                                    :href="route('orders.create')"
                                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700"
                                >
                                    Checkout
                                </Link>
                            </div>
                        </div>
                    </div>
                    
                    <EmptyCart v-else />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import CartItemsList from '@/Components/Cart/CartItemsList.vue'
import EmptyCart from '@/Components/Cart/EmptyCart.vue'

const props = defineProps({
    cartItems: Array,
    total: Number
})

const onCartUpdated = () => {
    // Atualizar carrinho
    router.reload()
}

const onItemRemoved = () => {
    // Atualizar carrinho
    router.reload()
}
</script>
```

### 5. Componentes Compartilhados
```vue
<!-- resources/js/Shared/CartCounter.vue -->
<template>
    <Link :href="route('cart.index')" class="relative">
        <ShoppingCartIcon class="w-6 h-6" />
        <span 
            v-if="count > 0"
            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
        >
            {{ count }}
        </span>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ShoppingCartIcon } from '@heroicons/vue/24/outline'

defineProps({
    count: {
        type: Number,
        default: 0
    }
})
</script>
```

```vue
<!-- resources/js/Shared/NavLink.vue -->
<template>
    <Link
        :href="href"
        :class="[
            'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none',
            active
                ? 'border-indigo-400 text-gray-900 focus:border-indigo-700'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300'
        ]"
    >
        <slot />
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    href: String,
    active: Boolean
})
</script>
```

---

## Frontend - Listagem de Produtos

### 1. Módulo Products - Componente Livewire
```bash
php artisan make:livewire ProductsList
```

```php
// app/Modules/Products/Livewire/ProductsList.php
<?php

namespace App\Modules\Products\Livewire;

use App\Modules\Products\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsList extends Component
{
    use WithPagination;
    
    public $search = '';
    public $activeOnly = true;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'activeOnly' => ['except' => true],
    ];

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->activeOnly, function ($query) {
                $query->where('active', true);
            })
            ->paginate(12);

        return view('products::livewire.products-list', compact('products'));
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedActiveOnly()
    {
        $this->resetPage();
    }
}
```

### 2. Módulo Products - View Livewire
```php
<!-- app/Modules/Products/Views/livewire/products-list.blade.php -->
<div>
    <div class="mb-6">
        <div class="flex gap-4">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Search products..."
                class="flex-1 px-4 py-2 border rounded-lg"
            >
            <label class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    wire:model.live="activeOnly"
                    class="rounded"
                >
                <span>Active only</span>
            </label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 mb-2">$ {{ number_format($product->price, 2) }}</p>
                    <p class="text-sm text-gray-500 mb-4">Stock: {{ $product->stock }}</p>
                    <a 
                        href="{{ route('products.show', $product->slug) }}"
                        class="block w-full bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700"
                    >
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500">No products found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
```

### 3. Página de Produtos
```php
<!-- resources/views/produtos/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Produtos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:produtos-listagem />
        </div>
    </div>
</x-app-layout>
```

### 4. Rota
```php
// routes/web.php
Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
```

---

## Frontend - Detalhe do Produto

### 1. Controller
```bash
php artisan make:controller ProdutoController
```

```php
// app/Http/Controllers/ProdutoController.php
class ProdutoController extends Controller
{
    public function index()
    {
        return view('produtos.index');
    }

    public function show(string $slug)
    {
        $produto = Produto::where('slug', $slug)->firstOrFail();
        return view('produtos.show', compact('produto'));
    }
}
```

### 2. Página de Detalhe
```php
<!-- resources/views/produtos/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $produto->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h1 class="text-3xl font-bold mb-4">{{ $produto->nome }}</h1>
                            <p class="text-2xl text-green-600 font-semibold mb-4">
                                R$ {{ number_format($produto->preco, 2, ',', '.') }}
                            </p>
                            <p class="text-gray-600 mb-6">
                                Estoque disponível: {{ $produto->estoque }} unidades
                            </p>
                            
                            @if($produto->ativo && $produto->estoque > 0)
                                <div class="space-y-4">
                                    <div>
                                        <label for="quantidade" class="block text-sm font-medium text-gray-700">
                                            Quantidade:
                                        </label>
                                        <input 
                                            type="number" 
                                            id="quantidade"
                                            min="1" 
                                            max="{{ $produto->estoque }}"
                                            value="1"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md"
                                        >
                                    </div>
                                    
                                    <button 
                                        id="adicionar-carrinho"
                                        data-produto-id="{{ $produto->id }}"
                                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors"
                                    >
                                        Adicionar ao Carrinho
                                    </button>
                                </div>
                            @else
                                <div class="bg-gray-100 p-4 rounded-lg">
                                    <p class="text-gray-600">
                                        @if(!$produto->ativo)
                                            Produto indisponível
                                        @else
                                            Produto fora de estoque
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        <div>
                            <!-- Aqui pode ir uma imagem do produto -->
                            <div class="bg-gray-200 h-64 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">Imagem do Produto</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('adicionar-carrinho').addEventListener('click', function() {
            const produtoId = this.dataset.produtoId;
            const quantidade = document.getElementById('quantidade').value;
            
            fetch('/carrinho/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    produto_id: produtoId,
                    quantidade: quantidade
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar contador do carrinho
                    updateCartCounter(data.total_items);
                    
                    // Mostrar mensagem de sucesso
                    alert('Produto adicionado ao carrinho!');
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao adicionar produto ao carrinho');
            });
        });
        
        function updateCartCounter(totalItems) {
            const counter = document.getElementById('cart-counter');
            if (counter) {
                counter.textContent = totalItems;
                counter.style.display = totalItems > 0 ? 'block' : 'none';
            }
        }
    </script>
    @endpush
</x-app-layout>
```

### 3. Header com Contador do Carrinho
```php
<!-- resources/views/layouts/navigation.blade.php -->
<!-- Adicionar no header -->
<a href="{{ route('carrinho.index') }}" class="relative">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
    </svg>
    <span id="cart-counter" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">0</span>
</a>
```

---

## Frontend - Carrinho e Pedidos

### 1. Controller do Carrinho
```bash
php artisan make:controller CarrinhoController
```

```php
// app/Http/Controllers/CarrinhoController.php
class CarrinhoController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1'
        ]);

        $produto = Produto::findOrFail($request->produto_id);
        
        if (!$produto->ativo || $produto->estoque < $request->quantidade) {
            return response()->json([
                'success' => false,
                'message' => 'Produto indisponível ou estoque insuficiente'
            ], 400);
        }

        $carrinho = session()->get('carrinho', []);
        $produtoId = $request->produto_id;
        
        if (isset($carrinho[$produtoId])) {
            $carrinho[$produtoId]['quantidade'] += $request->quantidade;
        } else {
            $carrinho[$produtoId] = [
                'produto_id' => $produtoId,
                'nome' => $produto->nome,
                'preco' => $produto->preco,
                'quantidade' => $request->quantidade
            ];
        }
        
        session()->put('carrinho', $carrinho);
        
        return response()->json([
            'success' => true,
            'total_items' => array_sum(array_column($carrinho, 'quantidade')),
            'message' => 'Produto adicionado ao carrinho'
        ]);
    }

    public function index()
    {
        $carrinho = session()->get('carrinho', []);
        $total = 0;
        
        foreach ($carrinho as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }
        
        return view('carrinho.index', compact('carrinho', 'total'));
    }

    public function remove(Request $request)
    {
        $carrinho = session()->get('carrinho', []);
        unset($carrinho[$request->produto_id]);
        session()->put('carrinho', $carrinho);
        
        return redirect()->route('carrinho.index');
    }

    public function finalizar()
    {
        $carrinho = session()->get('carrinho', []);
        
        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'Carrinho vazio');
        }

        DB::beginTransaction();
        
        try {
            $total = 0;
            $pedido = Pedido::create([
                'user_id' => auth()->id(),
                'total' => 0
            ]);

            foreach ($carrinho as $item) {
                $produto = Produto::findOrFail($item['produto_id']);
                
                if ($produto->estoque < $item['quantidade']) {
                    throw new \Exception("Estoque insuficiente para {$produto->nome}");
                }

                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco']
                ]);

                $total += $item['preco'] * $item['quantidade'];
                
                // Reduzir estoque
                $produto->decrement('estoque', $item['quantidade']);
            }

            $pedido->update(['total' => $total]);
            
            // Limpar carrinho
            session()->forget('carrinho');
            
            DB::commit();
            
            // Disparar job para envio de email
            dispatch(new EnviarEmailConfirmacaoPedido($pedido));
            
            return redirect()->route('pedidos.show', $pedido->id)
                ->with('success', 'Pedido realizado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('carrinho.index')
                ->with('error', 'Erro ao finalizar pedido: ' . $e->getMessage());
        }
    }
}
```

### 2. View do Carrinho
```php
<!-- resources/views/carrinho/index.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Carrinho de Compras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('carrinho') && count(session('carrinho')) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($carrinho as $item)
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex-1">
                                        <h3 class="font-semibold">{{ $item['nome'] }}</h3>
                                        <p class="text-gray-600">R$ {{ number_format($item['preco'], 2, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-gray-600">Qtd: {{ $item['quantidade'] }}</span>
                                        <span class="font-semibold">
                                            R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}
                                        </span>
                                        <form method="POST" action="{{ route('carrinho.remove') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="produto_id" value="{{ $item['produto_id'] }}">
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                Remover
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6 pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold">Total: R$ {{ number_format($total, 2, ',', '.') }}</span>
                                <form method="POST" action="{{ route('carrinho.finalizar') }}">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                                        Finalizar Pedido
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-600 mb-4">Seu carrinho está vazio</p>
                        <a href="{{ route('produtos.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Continuar Comprando
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
```

### 3. Módulo Orders - Controllers Granulares
```bash
# Criar controllers específicos para cada funcionalidade
php artisan make:controller CreateOrderController
php artisan make:controller UpdateOrderStatusController
php artisan make:controller ShowOrderController
php artisan make:controller ListOrdersController
php artisan make:controller ListAdminOrdersController
```

```php
// app/Modules/Orders/Controllers/CreateOrderController.php
<?php

namespace App\Modules\Orders\Controllers;

use App\Modules\Orders\Services\CreateOrderService;
use App\Modules\Orders\Requests\CreateOrderRequest;
use Illuminate\Http\RedirectResponse;

class CreateOrderController extends Controller
{
    public function __construct(
        private CreateOrderService $createOrderService
    ) {}

    public function store(CreateOrderRequest $request): RedirectResponse
    {
        $order = $this->createOrderService->execute($request->validated());
        
        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully');
    }
}
```

```php
// app/Modules/Orders/Controllers/UpdateOrderStatusController.php
<?php

namespace App\Modules\Orders\Controllers;

use App\Modules\Orders\Services\UpdateOrderStatusService;
use App\Modules\Orders\Requests\UpdateOrderStatusRequest;
use App\Modules\Orders\Models\Order;
use Illuminate\Http\RedirectResponse;

class UpdateOrderStatusController extends Controller
{
    public function __construct(
        private UpdateOrderStatusService $updateOrderStatusService
    ) {}

    public function update(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $this->updateOrderStatusService->execute($order, $request->validated());
        
        return redirect()->back()->with('success', 'Order status updated successfully');
    }
}
```

### 4. Módulo Orders - Services Granulares
```bash
# Criar services específicos para cada funcionalidade
php artisan make:service CreateOrderService
php artisan make:service UpdateOrderStatusService
php artisan make:service ShowOrderService
php artisan make:service ListOrdersService
```

```php
// app/Modules/Orders/Services/CreateOrderService.php
<?php

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Order;
use App\Modules\Products\Services\StockService;
use Illuminate\Support\Facades\DB;

class CreateOrderService
{
    public function __construct(
        private StockService $stockService
    ) {}

    public function execute(array $data): Order
    {
        DB::beginTransaction();
        
        try {
            $total = 0;
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => 0
            ]);

            foreach ($data['items'] as $item) {
                // Verificar estoque
                if (!$this->stockService->checkAvailability($item['product_id'], $item['quantity'])) {
                    throw new \Exception("Insufficient stock for product");
                }

                // Criar item do pedido
                $orderItem = $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price']
                ]);

                $total += $item['unit_price'] * $item['quantity'];
                
                // Reduzir estoque
                $this->stockService->reduceStock($item['product_id'], $item['quantity']);
            }

            $order->update(['total' => $total]);
            
            DB::commit();
            
            return $order;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
```

```php
// app/Modules/Orders/Services/UpdateOrderStatusService.php
<?php

namespace App\Modules\Orders\Services;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Jobs\SendOrderConfirmationEmail;

class UpdateOrderStatusService
{
    public function execute(Order $order, array $data): void
    {
        $order->update(['status' => $data['status']]);
        
        if ($data['status'] === 'paid') {
            dispatch(new SendOrderConfirmationEmail($order));
        }
    }
}
```

---

## Segurança - Policies

### 1. Policy para Pedido
```bash
php artisan make:policy PedidoPolicy --model=Pedido
```

```php
// app/Policies/PedidoPolicy.php
class PedidoPolicy
{
    public function view(User $user, Pedido $pedido)
    {
        return $user->id === $pedido->user_id || $user->isAdmin();
    }

    public function update(User $user, Pedido $pedido)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Pedido $pedido)
    {
        return $user->isAdmin();
    }
}
```

### 2. Policy para Produto
```bash
php artisan make:policy ProdutoPolicy --model=Produto
```

```php
// app/Policies/ProdutoPolicy.php
class ProdutoPolicy
{
    public function viewAny(User $user)
    {
        return true; // Produtos são públicos
    }

    public function view(User $user, Produto $produto)
    {
        return true; // Produtos são públicos
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Produto $produto)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Produto $produto)
    {
        return $user->isAdmin();
    }
}
```

### 3. Middleware Admin
```php
// app/Http/Middleware/AdminMiddleware.php
public function handle(Request $request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
    }
    
    return $next($request);
}
```

### 4. Registro das Policies
```php
// app/Providers/AuthServiceProvider.php
protected $policies = [
    Pedido::class => PedidoPolicy::class,
    Produto::class => ProdutoPolicy::class,
];
```

---

## Extras - Cache, Jobs e Testes

### 1. Cache de Produtos
```php
// app/Http/Controllers/ProdutoController.php
public function index()
{
    $produtos = Cache::remember('produtos.ativos', 60, function () {
        return Produto::ativos()->comEstoque()->get();
    });
    
    return view('produtos.index', compact('produtos'));
}
```

### 2. Job para Email
```bash
php artisan make:job EnviarEmailConfirmacaoPedido
```

```php
// app/Jobs/EnviarEmailConfirmacaoPedido.php
class EnviarEmailConfirmacaoPedido implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Pedido $pedido
    ) {}

    public function handle()
    {
        Mail::to($this->pedido->user->email)->send(
            new PedidoConfirmadoMail($this->pedido)
        );
    }
}
```

### 3. Mailable
```bash
php artisan make:mail PedidoConfirmadoMail
```

```php
// app/Mail/PedidoConfirmadoMail.php
class PedidoConfirmadoMail extends Mailable
{
    public function __construct(
        public Pedido $pedido
    ) {}

    public function build()
    {
        return $this->subject('Pedido Confirmado - ' . $this->pedido->codigo)
            ->view('emails.pedido-confirmado')
            ->with(['pedido' => $this->pedido]);
    }
}
```

### 4. Testes Feature
```bash
php artisan make:test PedidoTest
```

```php
// tests/Feature/PedidoTest.php
class PedidoTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_pode_criar_pedido()
    {
        $user = User::factory()->create(['role' => 'cliente']);
        $produto = Produto::factory()->create(['estoque' => 10]);
        
        $this->actingAs($user);
        
        $response = $this->post('/carrinho/add', [
            'produto_id' => $produto->id,
            'quantidade' => 2
        ]);
        
        $response->assertJson(['success' => true]);
        
        $response = $this->post('/carrinho/finalizar');
        
        $response->assertRedirect();
        $this->assertDatabaseHas('pedidos', [
            'user_id' => $user->id,
            'total' => $produto->preco * 2
        ]);
    }

    public function test_estoque_e_reduzido_ao_criar_pedido()
    {
        $user = User::factory()->create();
        $produto = Produto::factory()->create(['estoque' => 10]);
        
        $this->actingAs($user);
        
        $this->post('/carrinho/add', [
            'produto_id' => $produto->id,
            'quantidade' => 3
        ]);
        
        $this->post('/carrinho/finalizar');
        
        $produto->refresh();
        $this->assertEquals(7, $produto->estoque);
    }

    public function test_cliente_so_acessa_proprios_pedidos()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $pedido = Pedido::factory()->create(['user_id' => $user1->id]);
        
        $this->actingAs($user2);
        
        $response = $this->get("/pedidos/{$pedido->id}");
        
        $response->assertStatus(403);
    }
}
```

### 5. Testes Livewire
```bash
php artisan make:test ProdutosListagemTest
```

```php
// tests/Feature/ProdutosListagemTest.php
class ProdutosListagemTest extends TestCase
{
    use RefreshDatabase;

    public function test_busca_produtos_por_nome()
    {
        Produto::factory()->create(['nome' => 'Produto A']);
        Produto::factory()->create(['nome' => 'Produto B']);
        
        Livewire::test(ProdutosListagem::class)
            ->set('busca', 'Produto A')
            ->assertSee('Produto A')
            ->assertDontSee('Produto B');
    }

    public function test_filtro_somente_ativos()
    {
        Produto::factory()->create(['nome' => 'Produto Ativo', 'ativo' => true]);
        Produto::factory()->create(['nome' => 'Produto Inativo', 'ativo' => false]);
        
        Livewire::test(ProdutosListagem::class)
            ->set('somenteAtivos', true)
            ->assertSee('Produto Ativo')
            ->assertDontSee('Produto Inativo');
    }
}
```

### 6. Configuração de Queue
```php
// config/queue.php - Usar database driver para desenvolvimento
'default' => env('QUEUE_CONNECTION', 'database'),

// Executar migrations para tabelas de queue
php artisan queue:table
php artisan migrate

// Executar worker
php artisan queue:work
```

### 7. Rotas Finais
```php
// routes/web.php
Route::get('/', function () {
    return redirect()->route('produtos.index');
});

// Rotas públicas
Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index');
Route::get('/produtos/{slug}', [ProdutoController::class, 'show'])->name('produtos.show');

// Rotas autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
    Route::post('/carrinho/add', [CarrinhoController::class, 'add'])->name('carrinho.add');
    Route::post('/carrinho/remove', [CarrinhoController::class, 'remove'])->name('carrinho.remove');
    Route::post('/carrinho/finalizar', [CarrinhoController::class, 'finalizar'])->name('carrinho.finalizar');
    
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
});

// Rotas admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pedidos', [PedidoController::class, 'adminIndex'])->name('pedidos.index');
    Route::patch('/pedidos/{pedido}/status', [PedidoController::class, 'updateStatus'])->name('pedidos.update-status');
    
    Route::resource('produtos', AdminProdutoController::class);
});
```

---

## Comandos para Executar

```bash
# Instalar dependências
composer install
npm install

# Configurar banco
cp .env.example .env
php artisan key:generate
php artisan migrate

# Executar em desenvolvimento
npm run dev
php artisan serve
php artisan queue:work
```

---

## Estrutura Final do Projeto (SPA Modular Granular)

```
app/
├── Models/
│   └── BaseModel.php
├── Modules/
│   ├── Users/
│   │   ├── Controllers/
│   │   │   ├── CreateUserController.php
│   │   │   ├── UpdateUserController.php
│   │   │   ├── DeleteUserController.php
│   │   │   ├── ShowUserController.php
│   │   │   └── ListUsersController.php
│   │   ├── Models/
│   │   │   └── User.php
│   │   ├── Services/
│   │   │   ├── CreateUserService.php
│   │   │   ├── UpdateUserService.php
│   │   │   ├── DeleteUserService.php
│   │   │   ├── ShowUserService.php
│   │   │   └── ListUsersService.php
│   │   ├── Requests/
│   │   │   ├── CreateUserRequest.php
│   │   │   └── UpdateUserRequest.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   ├── Policies/
│   │   │   └── UserPolicy.php
│   │   └── routes.php
│   ├── Products/
│   │   ├── Controllers/
│   │   │   ├── CreateProductController.php
│   │   │   ├── UpdateProductController.php
│   │   │   ├── DeleteProductController.php
│   │   │   ├── ShowProductController.php
│   │   │   └── ListProductsController.php
│   │   ├── Models/
│   │   │   └── Product.php
│   │   ├── Services/
│   │   │   ├── CreateProductService.php
│   │   │   ├── UpdateProductService.php
│   │   │   ├── DeleteProductService.php
│   │   │   ├── ShowProductService.php
│   │   │   ├── ListProductsService.php
│   │   │   └── StockService.php
│   │   ├── Requests/
│   │   │   ├── StoreProductRequest.php
│   │   │   └── UpdateProductRequest.php
│   │   ├── Observers/
│   │   │   └── ProductObserver.php
│   │   ├── Policies/
│   │   │   └── ProductPolicy.php
│   │   ├── Livewire/
│   │   │   └── ProductsList.php
│   │   └── routes.php
│   ├── Orders/
│   │   ├── Controllers/
│   │   │   ├── CreateOrderController.php
│   │   │   ├── UpdateOrderStatusController.php
│   │   │   ├── ShowOrderController.php
│   │   │   ├── ListOrdersController.php
│   │   │   └── ListAdminOrdersController.php
│   │   ├── Models/
│   │   │   ├── Order.php
│   │   │   └── OrderItem.php
│   │   ├── Services/
│   │   │   ├── CreateOrderService.php
│   │   │   ├── UpdateOrderStatusService.php
│   │   │   ├── ShowOrderService.php
│   │   │   └── ListOrdersService.php
│   │   ├── Requests/
│   │   │   ├── CreateOrderRequest.php
│   │   │   └── UpdateOrderStatusRequest.php
│   │   ├── Policies/
│   │   │   └── OrderPolicy.php
│   │   ├── Jobs/
│   │   │   └── SendOrderConfirmationEmail.php
│   │   ├── Mail/
│   │   │   └── OrderConfirmationMail.php
│   │   └── routes.php
│   └── Cart/
│       ├── Controllers/
│       │   ├── AddToCartController.php
│       │   ├── RemoveFromCartController.php
│       │   ├── UpdateCartController.php
│       │   ├── ShowCartController.php
│       │   └── ClearCartController.php
│       ├── Services/
│       │   ├── AddToCartService.php
│       │   ├── RemoveFromCartService.php
│       │   ├── UpdateCartService.php
│       │   ├── ShowCartService.php
│       │   └── ClearCartService.php
│       ├── Requests/
│       │   ├── AddToCartRequest.php
│       │   ├── RemoveFromCartRequest.php
│       │   └── UpdateCartRequest.php
│       └── routes.php
└── Providers/
    └── ModuleServiceProvider.php

resources/
├── js/
│   ├── Pages/
│   │   ├── Users/
│   │   │   ├── Index.vue
│   │   │   ├── Create.vue
│   │   │   ├── Edit.vue
│   │   │   └── Show.vue
│   │   ├── Products/
│   │   │   ├── Index.vue
│   │   │   ├── Create.vue
│   │   │   ├── Edit.vue
│   │   │   └── Show.vue
│   │   ├── Orders/
│   │   │   ├── Index.vue
│   │   │   ├── Show.vue
│   │   │   └── Admin/
│   │   │       └── Index.vue
│   │   └── Cart/
│   │       └── Index.vue
│   ├── Components/
│   │   ├── Users/
│   │   │   ├── UsersTable.vue
│   │   │   ├── UserForm.vue
│   │   │   └── UserCard.vue
│   │   ├── Products/
│   │   │   ├── ProductsGrid.vue
│   │   │   ├── ProductsFilters.vue
│   │   │   ├── ProductForm.vue
│   │   │   ├── ProductCard.vue
│   │   │   ├── AddToCartForm.vue
│   │   │   └── ProductImage.vue
│   │   ├── Orders/
│   │   │   ├── OrdersList.vue
│   │   │   ├── OrderCard.vue
│   │   │   └── OrderStatusBadge.vue
│   │   └── Cart/
│   │       ├── CartItemsList.vue
│   │       ├── CartItem.vue
│   │       └── EmptyCart.vue
│   ├── Layouts/
│   │   ├── MainLayout.vue
│   │   └── AuthLayout.vue
│   ├── Shared/
│   │   ├── NavLink.vue
│   │   ├── CartCounter.vue
│   │   ├── UserMenu.vue
│   │   └── LoadingSpinner.vue
│   ├── app.js
│   └── ssr.js
└── css/
    └── app.css
```

## Principais Mudanças na Arquitetura

### 1. **UUID como Chave Primária**
- Todos os modelos estendem `BaseModel` com UUID
- Migrations usam `$table->uuid('id')->primary()`
- Relacionamentos usam `foreignUuid()`

### 2. **Soft Deletes**
- Implementado em todos os modelos
- Campo `deleted_at` em todas as tabelas
- Queries automáticas excluem registros deletados

### 3. **Padronização em Inglês**
- Tabelas: `products`, `orders`, `order_items`
- Campos: `name`, `price`, `stock`, `active`
- Status: `pending`, `paid`, `cancelled`
- Roles: `admin`, `customer`

### 4. **Arquitetura Modular Granular**
- Cada funcionalidade em seu próprio módulo
- **Controllers granulares**: Um controller por ação (CreateUserController, UpdateUserController, etc.)
- **Services granulares**: Um service por funcionalidade (CreateUserService, UpdateUserService, etc.)
- Estrutura consistente: Controllers, Models, Services, Requests, Policies, etc.
- Rotas modulares carregadas automaticamente
- Views organizadas por módulo

### 5. **Service Provider Modular**
- Carregamento automático de rotas e views
- Configuração centralizada de módulos
- Fácil adição de novos módulos

### 6. **Princípio de Responsabilidade Única**
- Cada controller tem apenas uma responsabilidade
- Cada service executa apenas uma operação específica
- Facilita manutenção, testes e reutilização
- Código mais limpo e organizado

### 7. **SPA Modular com Vue.js + Inertia.js**
- **Frontend SPA**: Single Page Application com Vue.js
- **Views Modulares**: Organizadas por módulo (Users/, Products/, Orders/, Cart/)
- **Componentes Reutilizáveis**: Componentes Vue.js organizados por funcionalidade
- **Inertia.js**: Comunicação seamless entre Laravel e Vue.js
- **SSR Support**: Server-Side Rendering para melhor SEO
- **Hot Reload**: Desenvolvimento rápido com Vite

## Comandos para Implementar

```bash
# 1. Instalar dependências Laravel
composer require ramsey/uuid

# 2. Instalar dependências Frontend
npm install @inertiajs/vue3 @vitejs/plugin-vue
npm install vue@next @vue/compiler-sfc
npm install @headlessui/vue @heroicons/vue

# 3. Criar estrutura de módulos backend
mkdir -p app/Modules/{Users,Products,Orders,Cart}
mkdir -p app/Modules/Users/{Controllers,Models,Services,Requests,Policies,Observers,Jobs,Mail,Livewire,Views}
mkdir -p app/Modules/Products/{Controllers,Models,Services,Requests,Policies,Observers,Jobs,Mail,Livewire,Views}
mkdir -p app/Modules/Orders/{Controllers,Models,Services,Requests,Policies,Observers,Jobs,Mail,Livewire,Views}
mkdir -p app/Modules/Cart/{Controllers,Models,Services,Requests,Policies,Observers,Jobs,Mail,Livewire,Views}

# 4. Criar estrutura de módulos frontend
mkdir -p resources/js/Pages/{Users,Products,Orders,Cart}
mkdir -p resources/js/Components/{Users,Products,Orders,Cart}
mkdir -p resources/js/Layouts
mkdir -p resources/js/Shared

# 5. Criar controllers granulares para Users
php artisan make:controller CreateUserController
php artisan make:controller UpdateUserController
php artisan make:controller DeleteUserController
php artisan make:controller ShowUserController
php artisan make:controller ListUsersController

# 4. Criar services granulares para Users
php artisan make:service CreateUserService
php artisan make:service UpdateUserService
php artisan make:service DeleteUserService
php artisan make:service ShowUserService
php artisan make:service ListUsersService

# 5. Criar controllers granulares para Products
php artisan make:controller CreateProductController
php artisan make:controller UpdateProductController
php artisan make:controller DeleteProductController
php artisan make:controller ShowProductController
php artisan make:controller ListProductsController

# 6. Criar services granulares para Products
php artisan make:service CreateProductService
php artisan make:service UpdateProductService
php artisan make:service DeleteProductService
php artisan make:service ShowProductService
php artisan make:service ListProductsService
php artisan make:service StockService

# 7. Criar controllers granulares para Orders
php artisan make:controller CreateOrderController
php artisan make:controller UpdateOrderStatusController
php artisan make:controller ShowOrderController
php artisan make:controller ListOrdersController
php artisan make:controller ListAdminOrdersController

# 8. Criar services granulares para Orders
php artisan make:service CreateOrderService
php artisan make:service UpdateOrderStatusService
php artisan make:service ShowOrderService
php artisan make:service ListOrdersService

# 9. Criar controllers granulares para Cart
php artisan make:controller AddToCartController
php artisan make:controller RemoveFromCartController
php artisan make:controller UpdateCartController
php artisan make:controller ShowCartController
php artisan make:controller ClearCartController

# 10. Criar services granulares para Cart
php artisan make:service AddToCartService
php artisan make:service RemoveFromCartService
php artisan make:service UpdateCartService
php artisan make:service ShowCartService
php artisan make:service ClearCartService

# 11. Executar migrations
php artisan migrate

# 12. Configurar Vite e Inertia.js
# - Configurar vite.config.js com aliases
# - Configurar app.js com Inertia
# - Criar layouts e componentes base

# 13. Registrar observers no AppServiceProvider
# 14. Configurar cache e queues
# 15. Executar build do frontend
npm run build

# 16. Executar testes
php artisan test
```

Este guia fornece uma implementação completa do sistema de catálogo com pedidos usando arquitetura modular, UUID, soft deletes e padronização em inglês, incluindo todas as funcionalidades solicitadas e extras valorizados.
