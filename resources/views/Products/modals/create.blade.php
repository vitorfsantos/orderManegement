<x-modal id="create-product-modal" title="Novo Produto" size="lg">
    <form id="create-product-form" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
                <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome do Produto *
                </label>
                <input 
                    type="text" 
                    id="create_name" 
                    name="name" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Digite o nome do produto"
                >
                <div id="create_name_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>

            <!-- Preço -->
            <div>
                <label for="create_price" class="block text-sm font-medium text-gray-700 mb-2">
                    Preço *
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">R$</span>
                    </div>
                    <input 
                        type="text" 
                        id="create_price" 
                        name="price" 
                        required
                        class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0,00"
                        oninput="formatPriceInput(this)"
                    >
                </div>
                <div id="create_price_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Estoque -->
            <div>
                <label for="create_stock" class="block text-sm font-medium text-gray-700 mb-2">
                    Estoque *
                </label>
                <input 
                    type="number" 
                    id="create_stock" 
                    name="stock" 
                    min="0"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Quantidade em estoque"
                >
                <div id="create_stock_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>

            <!-- Status -->
            <div>
                <label for="create_active" class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select 
                    id="create_active" 
                    name="active" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="1" selected>Ativo</option>
                    <option value="0">Inativo</option>
                </select>
                <div id="create_active_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <button type="button" onclick="closeModal('create-product-modal')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            Cancelar
        </button>
        <button type="button" onclick="submitCreateProduct()" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
            <i class="fas fa-save mr-2"></i>
            Criar Produto
        </button>
    </x-slot>
</x-modal>

<script>
function submitCreateProduct() {
    const form = document.getElementById('create-product-form');
    
    // Prepare form data (format price for submission)
    prepareFormData(form);
    
    const formData = new FormData(form);
    
    // Clear previous errors
    clearErrors('create_');
    
    fetch('{{ route("admin.products.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('create-product-modal');
            form.reset();
            showSuccess(data.message);
            // Reload the page to show the new product
            window.location.reload();
        } else {
            showErrors(data.errors, 'create_');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Erro ao criar produto. Tente novamente.');
    });
}
</script>
