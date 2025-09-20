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

                <!-- Orders -->
                <a href="#" 
                   class="flex items-center px-4 py-3 text-white rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-20">
                    <i class="fas fa-shopping-bag w-5 h-5 mr-3"></i>
                    <span class="font-medium">Pedidos</span>
                </a>
            </div>
        </nav>

        <!-- User Profile Section -->
        <div class="p-4 border-t border-white border-opacity-20 flex-shrink-0">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <span class="text-white font-semibold text-sm">{{ auth()->user()->initials() }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-medium text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-blue-100 text-xs truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            
            <!-- User Actions -->
            <div class="space-y-2">
                <a href="{{ route('settings.profile') }}" 
                   class="flex items-center px-3 py-2 text-blue-100 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 hover:text-white">
                    <i class="fas fa-cog w-4 h-4 mr-2"></i>
                    <span class="text-sm">Configurações</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center px-3 py-2 text-blue-100 rounded-lg transition-all duration-200 hover:bg-white hover:bg-opacity-10 hover:text-white">
                        <i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i>
                        <span class="text-sm">Sair</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile overlay -->
    <div class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden lg:hidden" id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Mobile Header -->
    <header class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-white shadow-sm border-b border-gray-200 px-4 py-3 flex items-center justify-between">
        <button onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-white text-sm"></i>
            </div>
            <span class="text-gray-900 font-semibold">OrdersApp</span>
        </div>
        
        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
            <span class="text-gray-600 font-semibold text-sm">{{ auth()->user()->initials() }}</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="lg:ml-64 h-screen bg-gray-50 overflow-y-auto pt-16 lg:pt-0">
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
</body>
</html>