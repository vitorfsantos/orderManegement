<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h2>
    <div class="grid grid-cols-2 gap-4">
        <a href="{{ route('products.index') }}" class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200 text-center">
            <i class="fas fa-box text-blue-600 text-2xl mb-2"></i>
            <p class="text-sm font-medium text-blue-900">Produtos</p>
        </a>
        <a href="{{ route('orders.index') }}" class="p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200 text-center">
            <i class="fas fa-shopping-bag text-green-600 text-2xl mb-2"></i>
            <p class="text-sm font-medium text-green-900">Pedidos</p>
        </a>
        <a href="{{ route('users.index') }}" class="p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200 text-center">
            <i class="fas fa-users text-purple-600 text-2xl mb-2"></i>
            <p class="text-sm font-medium text-purple-900">Usuários</p>
        </a>
        <a href="#" class="p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200 text-center">
            <i class="fas fa-chart-bar text-yellow-600 text-2xl mb-2"></i>
            <p class="text-sm font-medium text-yellow-900">Relatórios</p>
        </a>
    </div>
</div>
