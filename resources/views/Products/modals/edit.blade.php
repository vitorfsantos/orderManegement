<x-modal id="edit-product-modal" title="Editar Produto" size="lg">
    <form id="edit-product-form" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" id="edit_product_id" name="product_id">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome do Produto *
                </label>
                <input 
                    type="text" 
                    id="edit_name" 
                    name="name" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Digite o nome do produto"
                >
                <div id="edit_name_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>

            <!-- Preço -->
            <div>
                <label for="edit_price" class="block text-sm font-medium text-gray-700 mb-2">
                    Preço *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">R$</span>
                    </div>
                    <input 
                        type="text" 
                        id="edit_price" 
                        name="price" 
                        required
                        class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0,00"
                        oninput="formatPriceInput(this)"
                    >
                </div>
                <div id="edit_price_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Estoque -->
            <div>
                <label for="edit_stock" class="block text-sm font-medium text-gray-700 mb-2">
                    Estoque *
                </label>
                <input 
                    type="number" 
                    id="edit_stock" 
                    name="stock" 
                    min="0"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Quantidade em estoque"
                >
                <div id="edit_stock_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>

            <!-- Status -->
            <div>
                <label for="edit_active" class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select 
                    id="edit_active" 
                    name="active" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
                <div id="edit_active_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>
        </div>

        <!-- Informações atuais -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Informações Atuais</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Slug:</span>
                    <span id="edit_slug" class="ml-2 text-gray-900"></span>
                </div>
                <div>
                    <span class="text-gray-600">Criado em:</span>
                    <span id="edit_created_at" class="ml-2 text-gray-900"></span>
                </div>
                <div>
                    <span class="text-gray-600">Última atualização:</span>
                    <span id="edit_updated_at" class="ml-2 text-gray-900"></span>
                </div>
                <div>
                    <span class="text-gray-600">Pedidos associados:</span>
                    <span id="edit_orders_count" class="ml-2 text-gray-900"></span>
                </div>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <button type="button" onclick="closeModal('edit-product-modal')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            Cancelar
        </button>
        <button type="button" onclick="submitEditProduct()" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
            <i class="fas fa-save mr-2"></i>
            Salvar Alterações
        </button>
    </x-slot>
</x-modal>

