<x-layouts.app :title="__('Meus Pedidos')">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Meus Pedidos</h1>
                    <p class="text-gray-600 mt-1">Acompanhe o histórico dos seus pedidos</p>
                </div>
                <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Continuar Comprando
                </a>
            </div>

            @if($orders->count() > 0)
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Pedido #{{ $order->id }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-bold text-indigo-600">
                                        R$ {{ number_format($order->total, 2, ',', '.') }}
                                    </span>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($item->product->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                                <p class="text-sm text-gray-600">Qtd: {{ $item->quantity }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">
                                                R$ {{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                R$ {{ number_format($item->unit_price, 2, ',', '.') }} cada
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                    Ver detalhes do pedido <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                <!-- Nenhum pedido -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shopping-bag text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum pedido encontrado</h3>
                    <p class="text-gray-600 mb-6">Você ainda não fez nenhum pedido</p>
                    <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Fazer Primeiro Pedido
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
