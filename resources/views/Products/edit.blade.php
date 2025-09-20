<x-layouts.app :title="__('Editar Produto')">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Editar Produto</h1>
                        <p class="text-gray-600 mt-1">Atualize as informações do produto</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-eye mr-2"></i>
                            Ver Produto
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome do Produto *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $product->name) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            placeholder="Digite o nome do produto"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preço -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Preço *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input 
                                type="number" 
                                id="price" 
                                name="price" 
                                value="{{ old('price', $product->price) }}"
                                step="0.01"
                                min="0.01"
                                required
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                                placeholder="0,00"
                            >
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Estoque -->
                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                            Estoque *
                        </label>
                        <input 
                            type="number" 
                            id="stock" 
                            name="stock" 
                            value="{{ old('stock', $product->stock) }}"
                            min="0"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock') border-red-500 @enderror"
                            placeholder="Quantidade em estoque"
                        >
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="active" class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select 
                            id="active" 
                            name="active" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('active') border-red-500 @enderror"
                        >
                            <option value="1" {{ old('active', $product->active) ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ !old('active', $product->active) ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informações atuais -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Informações Atuais</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Slug:</span>
                            <span class="ml-2 text-gray-900">{{ $product->slug }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Criado em:</span>
                            <span class="ml-2 text-gray-900">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Última atualização:</span>
                            <span class="ml-2 text-gray-900">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Pedidos associados:</span>
                            <span class="ml-2 text-gray-900">{{ $product->orderItems()->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('products.show', $product->slug) }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
