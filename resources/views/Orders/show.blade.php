<x-layouts.app :title="__('Detalhes do Pedido')">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detalhes do Pedido</h1>
                    <p class="text-gray-600 mt-1">Pedido #{{ $order->id }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar aos Pedidos
                    </a>
                    <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Continuar Comprando
                    </a>
                </div>
            </div>

            <!-- Informações do Pedido -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Data do Pedido</h3>
                    <p class="text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Status</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Total</h3>
                    <p class="text-2xl font-bold text-indigo-600">
                        R$ {{ number_format($order->total, 2, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Itens do Pedido -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Itens do Pedido</h2>
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($item->product->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-gray-600">Preço unitário: R$ {{ number_format($item->unit_price, 2, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <div class="mb-2">
                                    <span class="text-lg font-bold text-gray-900">Qtd: {{ $item->quantity }}</span>
                                </div>
                                <div>
                                    <span class="text-xl font-bold text-indigo-600">
                                        R$ {{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Resumo do Pedido -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Resumo do Pedido</h3>
                        <p class="text-gray-600">{{ $order->orderItems->count() }} {{ $order->orderItems->count() === 1 ? 'item' : 'itens' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-bold text-indigo-600">
                            R$ {{ number_format($order->total, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
