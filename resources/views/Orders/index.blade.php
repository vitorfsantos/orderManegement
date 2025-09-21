<x-layouts.app :title="__('Meus Pedidos')">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        @auth
                            @if(auth()->user()->isAdmin())
                                Todos os Pedidos
                            @else
                                Meus Pedidos
                            @endif
                        @endauth
                    </h1>
                    <p class="text-gray-600 mt-1">
                        @auth
                            @if(auth()->user()->isAdmin())
                                Gerencie todos os pedidos do sistema
                            @else
                                Acompanhe o histórico dos seus pedidos
                            @endif
                        @endauth
                    </p>
                </div>
                <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Continuar Comprando
                </a>
            </div>

            @auth
                @if(auth()->user()->isAdmin())
                    <!-- Filtros para Admin -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <form method="GET" action="{{ route('orders.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Usuário</label>
                                <select name="user_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos os usuários</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Todos os status</option>
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data Inicial</label>
                                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data Final</label>
                                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Produto</label>
                                <input type="text" name="product_name" value="{{ request('product_name') }}" 
                                       placeholder="Nome do produto" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="md:col-span-2 lg:col-span-5 flex gap-2">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-search mr-2"></i>Filtrar
                                </button>
                                <a href="{{ route('orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Limpar
                                </a>
                            </div>
                        </form>
                    </div>
                @endif
            @endauth

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
                                    @auth
                                        @if(auth()->user()->isAdmin())
                                            <p class="text-sm text-blue-600 font-medium">
                                                Cliente: {{ $order->user->name }}
                                            </p>
                                        @endif
                                    @endauth
                                </div>
                                <div class="text-right">
                                    <span class="text-2xl font-bold text-indigo-600">
                                        R$ {{ number_format($order->total, 2, ',', '.') }}
                                    </span>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'paid') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($order->status === 'pending') Pendente
                                            @elseif($order->status === 'paid') Pago
                                            @elseif($order->status === 'cancelled') Cancelado
                                            @else {{ ucfirst($order->status) }}
                                            @endif
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

                            <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                                <button onclick="showOrderDetails('{{ $order->id }}')" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                    Ver detalhes do pedido <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                                
                                @auth
                                    @if(auth()->user()->isAdmin() && $order->status === 'pending')
                                        <div class="flex gap-2">
                                            <button type="button" 
                                                    class="bg-green-600 text-white px-3 py-1 rounded-md text-sm hover:bg-green-700 transition-colors"
                                                    onclick="showPaymentModal('{{ $order->id }}', '{{ number_format($order->total, 2, ',', '.') }}', '{{ $order->user->name }}')">
                                                <i class="fas fa-check mr-1"></i>Marcar como Pago
                                            </button>
                                            <button type="button" 
                                                    class="bg-red-600 text-white px-3 py-1 rounded-md text-sm hover:bg-red-700 transition-colors"
                                                    onclick="showCancelModal('{{ $order->id }}', '{{ number_format($order->total, 2, ',', '.') }}', '{{ $order->user->name }}')">
                                                <i class="fas fa-times mr-1"></i>Cancelar
                                            </button>
                                        </div>
                                    @endif
                                @endauth
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
    
    <!-- Order Details Modal -->
    <x-order-details-modal />
    
    <!-- Payment Confirmation Modal -->
    <x-payment-confirmation-modal />
    
    <!-- Cancel Order Modal -->
    <x-cancel-order-modal />
</x-layouts.app>

