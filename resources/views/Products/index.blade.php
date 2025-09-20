<x-layouts.app :title="__('Produtos')">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Produtos</h1>
                    <p class="text-gray-600 mt-1">Gerencie seu catálogo de produtos</p>
                </div>
                <button class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Novo Produto
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-lg text-gray-900">{{ $product->name }}</h3>
                                <span class="px-2 py-1 text-xs rounded-full {{ $product->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <p class="text-2xl font-bold text-indigo-600">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                <p class="text-sm text-gray-500 flex items-center">
                                    <i class="fas fa-box mr-1"></i>
                                    Estoque: {{ $product->stock }}
                                </p>
                            </div>
                            
                            <div class="flex space-x-2">
                                <a 
                                    href="{{ route('products.show', $product->slug) }}"
                                    class="flex-1 bg-blue-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center"
                                >
                                    Ver Detalhes
                                </a>
                                <button class="bg-gray-100 text-gray-600 px-3 py-2 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum produto encontrado</h3>
                        <p class="text-gray-500 mb-4">Comece adicionando seu primeiro produto ao catálogo.</p>
                        <button class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Adicionar Produto
                        </button>
                    </div>
                @endforelse
            </div>

            @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
