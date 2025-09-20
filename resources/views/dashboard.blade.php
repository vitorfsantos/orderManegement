<x-layouts.app :title="__('Dashboard')">
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Bem-vindo de volta, {{ auth()->user()->name }}!</h1>
                    <p class="text-blue-100">Aqui está um resumo do seu sistema de gestão de pedidos.</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chart-line text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Products -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total de Produtos</p>
                        <p class="text-2xl font-bold text-gray-900">24</p>
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +12% este mês
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pedidos Hoje</p>
                        <p class="text-2xl font-bold text-gray-900">8</p>
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +3 desde ontem
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Receita Hoje</p>
                        <p class="text-2xl font-bold text-gray-900">R$ 2.450</p>
                        <p class="text-xs text-green-600 flex items-center mt-1">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +18% este mês
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Usuários Ativos</p>
                        <p class="text-2xl font-bold text-gray-900">156</p>
                        <p class="text-xs text-red-600 flex items-center mt-1">
                            <i class="fas fa-arrow-down mr-1"></i>
                            -2% esta semana
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Orders -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Pedidos Recentes</h2>
                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-700">Ver todos</a>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Pedido #001</p>
                                <p class="text-xs text-gray-500">Cliente: João Silva</p>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-green-600">R$ 89,90</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Pedido #002</p>
                                <p class="text-xs text-gray-500">Cliente: Maria Santos</p>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-yellow-600">R$ 156,50</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-truck text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Pedido #003</p>
                                <p class="text-xs text-gray-500">Cliente: Pedro Costa</p>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-blue-600">R$ 234,00</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h2>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('products.index') }}" class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200 text-center">
                        <i class="fas fa-box text-blue-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-blue-900">Produtos</p>
                    </a>
                    <a href="#" class="p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200 text-center">
                        <i class="fas fa-shopping-bag text-green-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-green-900">Pedidos</p>
                    </a>
                    <a href="#" class="p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200 text-center">
                        <i class="fas fa-users text-purple-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-purple-900">Usuários</p>
                    </a>
                    <a href="#" class="p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200 text-center">
                        <i class="fas fa-chart-bar text-yellow-600 text-2xl mb-2"></i>
                        <p class="text-sm font-medium text-yellow-900">Relatórios</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
