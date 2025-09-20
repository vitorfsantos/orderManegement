<x-layouts.app :title="$product->name">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                            <i class="fas fa-box mr-2"></i>
                            Produtos
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-1"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Product Details -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Product Image -->
                <div>
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 h-96 rounded-xl flex items-center justify-center">
                        <div class="text-center">
                            <i class="fas fa-image text-6xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">Imagem do Produto</p>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="space-y-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                        <div class="flex items-center space-x-4 mb-4">
                            <span class="px-3 py-1 text-sm rounded-full {{ $product->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->active ? 'Ativo' : 'Inativo' }}
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-box mr-1"></i>
                                Estoque: {{ $product->stock }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-4xl font-bold text-indigo-600 mb-2">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-600">Preço unitário</p>
                    </div>

                    @if($product->active && $product->stock > 0)
                        <div class="space-y-4">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Quantidade:
                                </label>
                                <input 
                                    type="number" 
                                    id="quantity"
                                    min="1" 
                                    max="{{ $product->stock }}"
                                    value="1"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                            </div>
                            
                            <div class="flex space-x-3">
                                <button 
                                    id="add-to-cart"
                                    data-product-id="{{ $product->id }}"
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center justify-center"
                                >
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Adicionar ao Carrinho
                                </button>
                                
                                <button class="bg-gray-100 text-gray-600 px-4 py-3 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-100 p-6 rounded-lg text-center">
                            <i class="fas fa-exclamation-triangle text-3xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 font-medium">
                                @if(!$product->active)
                                    Produto indisponível
                                @else
                                    Fora de estoque
                                @endif
                            </p>
                        </div>
                    @endif

                    <!-- Product Actions -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <button class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-edit mr-2"></i>
                                Editar
                            </button>
                            <button class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-copy mr-2"></i>
                                Duplicar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Description -->
            @if($product->description)
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Descrição</h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @auth
        <script>
            document.getElementById('add-to-cart')?.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const quantity = document.getElementById('quantity').value;
                
                addToCart(productId, quantity);
            });
        </script>
        <script src="{{ asset('js/cart.js') }}"></script>
    @endauth
</x-layouts.app>