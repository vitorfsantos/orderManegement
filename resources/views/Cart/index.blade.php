<x-layouts.app :title="__('Carrinho de Compras')">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Carrinho de Compras</h1>
                    <p class="text-gray-600 mt-1">Revise seus itens antes de finalizar o pedido</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Continuar Comprando
                </a>
            </div>

            <!-- Mensagens de Sessão -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if(session('carrinho') && count(session('carrinho')) > 0)
                <div class="space-y-4">
                    @foreach($carrinho as $item)
                        <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($item['nome'], 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $item['nome'] }}</h3>
                                    <p class="text-gray-600">R$ {{ number_format($item['preco'], 2, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-600">Qtd:</span>
                                    <span class="font-semibold text-gray-900">{{ $item['quantidade'] }}</span>
                                </div>
                                
                                <div class="text-right">
                                    <span class="text-lg font-bold text-indigo-600">
                                        R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}
                                    </span>
                                </div>
                                
                                <form method="POST" action="{{ route('cart.remove') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="produto_id" value="{{ $item['produto_id'] }}">
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Remover do carrinho">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Resumo do pedido -->
                <div class="mt-8 bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Total do Pedido</h3>
                            <p class="text-gray-600">{{ count($carrinho) }} {{ count($carrinho) === 1 ? 'item' : 'itens' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-3xl font-bold text-indigo-600">
                                R$ {{ number_format($total, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('products.index') }}" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors text-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Continuar Comprando
                        </a>
                        <form method="POST" action="{{ route('orders.finish') }}" class="flex-1" id="finish-order-form">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                <i class="fas fa-credit-card mr-2"></i>
                                Finalizar Pedido
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Carrinho vazio -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shopping-cart text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Seu carrinho está vazio</h3>
                    <p class="text-gray-600 mb-6">Adicione alguns produtos para começar suas compras</p>
                    <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Ver Produtos
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
