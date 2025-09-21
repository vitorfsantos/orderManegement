@props(['orders' => []])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Pedidos Recentes</h2>
        <a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">Ver todos</a>
    </div>
    <div class="space-y-3">
        @forelse($orders as $order)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $order['status_color'] === 'yellow' ? 'bg-yellow-100' : ($order['status_color'] === 'blue' ? 'bg-blue-100' : ($order['status_color'] === 'green' ? 'bg-green-100' : ($order['status_color'] === 'purple' ? 'bg-purple-100' : 'bg-gray-100'))) }}">
                        <i class="{{ $order['status_icon'] }} text-sm {{ $order['status_color'] === 'yellow' ? 'text-yellow-600' : ($order['status_color'] === 'blue' ? 'text-blue-600' : ($order['status_color'] === 'green' ? 'text-green-600' : ($order['status_color'] === 'purple' ? 'text-purple-600' : 'text-gray-600'))) }}"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Pedido #{{ substr($order['code'], 0, 8) }}</p>
                        <p class="text-xs text-gray-500">Cliente: {{ $order['customer_name'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm font-medium text-gray-900">R$ {{ number_format($order['total'], 2, ',', '.') }}</span>
                    <p class="text-xs text-gray-500">{{ $order['created_at']->format('d/m H:i') }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-shopping-bag text-4xl mb-2"></i>
                <p>Nenhum pedido encontrado</p>
            </div>
        @endforelse
    </div>
</div>
