<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-blue-600 via-indigo-600 to-purple-700 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col h-screen" id="sidebar">
        <!-- Toggle button for mobile -->
        <button class="lg:hidden absolute top-4 right-4 text-white hover:text-gray-200" onclick="toggleSidebar()">
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Logo/Brand -->
        <div class="flex items-center justify-center h-16 px-4 border-b border-white border-opacity-20 flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-white text-lg"></i>
                </div>
                <span class="text-white text-xl font-bold">OrdersApp</span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6">
            <div class="space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('dashboard') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                    <i class="fas fa-home w-5 h-5 mr-3"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Users -->
                @if(auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}" 
                   class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('users.*') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    <span class="font-medium">Usuários</span>
                </a>
                @endif

                <!-- Products -->
                <a href="{{ route('products.index') }}" 
                   class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('products.*') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                    <i class="fas fa-box w-5 h-5 mr-3"></i>
                    <span class="font-medium">Produtos</span>
                </a>

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" 
                   class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('cart.*') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                    <div class="relative">
                        <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                        <span id="cart-badge" class="absolute -top-2 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">
                            <span id="cart-count">0</span>
                        </span>
                    </div>
                    <span class="font-medium">Carrinho</span>
                </a>

                <!-- Orders -->
                <a href="{{ route('orders.index') }}" 
                   class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-20 {{ request()->routeIs('orders.*') ? 'bg-white bg-opacity-20 shadow-lg' : '' }}">
                    <i class="fas fa-shopping-bag w-5 h-5 mr-3"></i>
                    <span class="font-medium">Pedidos</span>
                </a>
            </div>
        </nav>

    </div>

    <!-- Mobile overlay -->
    <div class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden lg:hidden" id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-40 bg-white shadow-sm border-b border-gray-200 lg:left-64">
        <div class="px-4 py-3 flex items-center justify-between">
            <!-- Mobile menu button -->
            <button onclick="toggleSidebar()" class="lg:hidden text-gray-600 hover:text-gray-900">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <!-- Desktop: Empty space for balance -->
            <div class="hidden lg:block"></div>
            
            <!-- Right side: User info, Cart, and Logout -->
            <div class="flex items-center space-x-4">
                <!-- Cart Icon with Counter -->
                <a href="{{ route('cart.index') }}" class="relative group">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                        <i class="fas fa-shopping-cart text-gray-600"></i>
                    </div>
                    <span id="cart-badge-header" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">
                        <span id="cart-count-header">0</span>
                    </span>
                    <div class="absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="p-3 text-sm text-gray-600">
                            <div class="font-medium text-gray-900">Carrinho</div>
                            <div id="cart-preview" class="mt-1">Nenhum item</div>
                        </div>
                    </div>
                </a>
                
                <!-- User Info Dropdown -->
                <div class="relative group">
                    <div class="flex items-center space-x-3 cursor-pointer">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ auth()->user()->initials() }}</span>
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-gray-900 font-medium text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-gray-500 text-xs">{{ auth()->user()->email }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute top-full right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="py-2">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <a href="{{ route('settings.profile') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-cog w-4 h-4 mr-3 text-gray-400"></i>
                                Configurações
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-sign-out-alt w-4 h-4 mr-3 text-gray-400"></i>
                                    Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="lg:ml-64 h-screen bg-gray-50 overflow-y-auto pt-16">
        <div class="p-6 h-full">
            {{ $slot }}
        </div>
    </main>


    <!-- JavaScript for Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleButton = event.target.closest('[onclick="toggleSidebar()"]');
            
            if (window.innerWidth < 1024 && !sidebar.contains(event.target) && !toggleButton) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
            }
        });
    </script>

    <!-- Cart JavaScript -->
    <script src="{{ asset('js/cart.js') }}"></script>
</body>
</html>