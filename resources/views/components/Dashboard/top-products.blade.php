@props(['topProducts' => []])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Produtos Mais Vendidos</h2>
        <a href="{{ route('products.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">Ver todos</a>
    </div>
    <div class="space-y-3">
        @forelse($topProducts as $index => $product)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold text-indigo-600">#{{ $index + 1 }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $product['name'] }}</p>
                        <p class="text-xs text-gray-500">{{ $product['orders_count'] }} pedidos</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm font-medium text-gray-900">R$ {{ number_format($product['price'], 2, ',', '.') }}</span>
                    <p class="text-xs text-gray-500">Estoque: {{ $product['stock'] }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-box text-4xl mb-2"></i>
                <p>Nenhum produto encontrado</p>
            </div>
        @endforelse
    </div>
</div>
