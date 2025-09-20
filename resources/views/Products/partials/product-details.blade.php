<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informações principais -->
    <div class="lg:col-span-2">
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Produto</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nome do Produto</label>
                    <p class="mt-1 text-gray-900">{{ $product->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                    <p class="mt-1 text-gray-900 font-mono">{{ $product->slug }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Preço</label>
                    <p class="mt-1 text-2xl font-bold text-indigo-600">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estoque</label>
                    <div class="mt-1 flex items-center">
                        <span class="text-lg font-semibold text-gray-900">{{ $product->stock }}</span>
                        <span class="ml-2 text-sm text-gray-500">unidades</span>
                        @if($product->stock > 0)
                            <span class="ml-3 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Em estoque
                            </span>
                        @else
                            <span class="ml-3 px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                Sem estoque
                            </span>
                        @endif
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="mt-1">
                        <span class="px-3 py-1 text-sm rounded-full {{ $product->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avatar e informações adicionais -->
    <div class="space-y-6">
        <!-- Avatar do produto -->
        <div class="bg-gray-50 rounded-lg p-6 text-center">
            <div class="w-24 h-24 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                {{ strtoupper(substr($product->name, 0, 2)) }}
            </div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
            <p class="text-gray-600">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
        </div>

        <!-- Informações do sistema -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Sistema</h3>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">ID do Produto</label>
                    <p class="mt-1 text-sm text-gray-600 font-mono">{{ $product->id }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Criado em</label>
                    <p class="mt-1 text-gray-900">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Última atualização</label>
                    <p class="mt-1 text-gray-900">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pedidos associados</label>
                    <p class="mt-1 text-gray-900">{{ $product->orderItems()->count() }} pedidos</p>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
            
            <div class="space-y-3">
                <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Ver Produto Público
                </a>
                
                <button onclick="editProduct('{{ $product->id }}')" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Produto
                </button>
                
                @if($product->orderItems()->count() === 0)
                    <button onclick="deleteProduct('{{ $product->id }}', '{{ $product->name }}')" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>
                        Remover Produto
                    </button>
                @else
                    <div class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-lg flex items-center justify-center cursor-not-allowed">
                        <i class="fas fa-lock mr-2"></i>
                        Não é possível remover produto com pedidos
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
