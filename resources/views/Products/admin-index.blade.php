<x-layouts.app :title="__('Produtos')">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Produtos</h1>
                    <p class="text-gray-600 mt-1">Gerencie os produtos do sistema</p>
                </div>
                <button onclick="openModal('create-product-modal')" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Novo Produto
                </button>
            </div>

            <!-- Filtros -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                <form method="GET" class="flex-1">
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Buscar por nome..."
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <div class="flex gap-2">
                    <a href="{{ route('admin.products.index') }}" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 {{ !$active && !$inStock ? 'bg-blue-50 border-blue-300 text-blue-700' : 'text-gray-700' }}">
                        Todos
                    </a>
                    <a href="{{ route('admin.products.index', ['active' => '1']) }}" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 {{ $active === '1' ? 'bg-blue-50 border-blue-300 text-blue-700' : 'text-gray-700' }}">
                        Ativos
                    </a>
                    <a href="{{ route('admin.products.index', ['active' => '0']) }}" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 {{ $active === '0' ? 'bg-blue-50 border-blue-300 text-blue-700' : 'text-gray-700' }}">
                        Inativos
                    </a>
                    <a href="{{ route('admin.products.index', ['in_stock' => '1']) }}" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 {{ $inStock ? 'bg-blue-50 border-blue-300 text-blue-700' : 'text-gray-700' }}">
                        Em Estoque
                    </a>
                </div>
            </div>
            
            <!-- Lista de produtos -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Produto</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Preço</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Estoque</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Criado em</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            {{ strtoupper(substr($product->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $product->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-gray-600">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->stock }} unidades
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $product->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $product->active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-gray-600">{{ $product->created_at->format('d/m/Y') }}</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('products.show', $product->slug) }}" class="text-blue-600 hover:text-blue-800" title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="editProduct('{{ $product->id }}')" class="text-indigo-600 hover:text-indigo-800" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteProduct('{{ $product->id }}', '{{ $product->name }}')" class="text-red-600 hover:text-red-800" title="Remover">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-box text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum produto encontrado</h3>
                                    <p class="text-gray-500 mb-4">
                                        @if($search)
                                            Nenhum produto encontrado para "{{ $search }}"
                                        @else
                                            Comece adicionando o primeiro produto ao sistema.
                                        @endif
                                    </p>
                                    <button onclick="openModal('create-product-modal')" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                                        <i class="fas fa-plus mr-2"></i>
                                        Adicionar Produto
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    @include('Products.modals.create')
    @include('Products.modals.edit')

    <!-- JavaScript -->
    <script src="{{ asset('js/products.js') }}"></script>
</x-layouts.app>
