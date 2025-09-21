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
            <div class="max-w-5xl mx-auto">
                <!-- Product Info -->
                <div class="space-y-8">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                        <div class="flex items-center justify-center space-x-6 mb-6">
                            <span class="px-4 py-2 text-sm font-medium rounded-full {{ $product->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-circle mr-2 text-xs"></i>
                                {{ $product->active ? 'Disponível' : 'Indisponível' }}
                            </span>
                            <span class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-full">
                                <i class="fas fa-box mr-2"></i>
                                {{ $product->stock }} unidades em estoque
                            </span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-2xl p-8 text-center border border-indigo-100">
                        <p class="text-5xl font-bold text-indigo-600 mb-3">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </p>
                        <p class="text-lg text-gray-600 font-medium">Preço unitário</p>
                    </div>

                    @if($product->active && $product->stock > 0)
                        <div class="space-y-6">
                            <div>
                                <label for="quantity" class="block text-lg font-semibold text-gray-700 mb-3">
                                    Quantidade:
                                </label>
                                <input 
                                    type="number" 
                                    id="quantity"
                                    min="1" 
                                    max="{{ $product->stock }}"
                                    value="1"
                                    class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-center font-semibold"
                                >
                            </div>
                            
                            <button 
                                id="add-to-cart"
                                data-product-id="{{ $product->id }}"
                                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center justify-center text-lg font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                            >
                                <i class="fas fa-shopping-cart mr-3"></i>
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-red-50 to-orange-50 p-8 rounded-2xl text-center border border-red-100">
                            <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                            <p class="text-xl text-gray-700 font-semibold">
                                @if(!$product->active)
                                    Produto indisponível
                                @else
                                    Fora de estoque
                                @endif
                            </p>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Product Description -->
            @if($product->description)
                <div class="mt-12 pt-8 border-t-2 border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Descrição do Produto</h2>
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                        <p class="text-lg text-gray-700 leading-relaxed text-center">{{ $product->description }}</p>
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