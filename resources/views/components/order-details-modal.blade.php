<x-modal id="orderDetailsModal" title="Detalhes do Pedido" size="xl">
    <div id="orderDetailsContent">
        <!-- Loading spinner -->
        <div id="loadingSpinner" class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <span class="ml-2 text-gray-600">Carregando detalhes...</span>
        </div>
        
        <!-- Error message -->
        <div id="errorMessage" class="hidden text-center py-8">
            <div class="text-red-600 mb-4">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <p class="text-gray-600">Erro ao carregar detalhes do pedido.</p>
        </div>
        
        <!-- Order details content -->
        <div id="orderDetails" class="hidden">
            <!-- Order Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Data do Pedido</h3>
                    <p class="text-gray-600" id="orderDate"></p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Status</h3>
                    <span id="orderStatus" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"></span>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Total</h3>
                    <p class="text-2xl font-bold text-indigo-600" id="orderTotal"></p>
                </div>
            </div>
            
            <!-- Customer Info (for admin) -->
            <div id="customerInfo" class="hidden bg-blue-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-900 mb-2">Cliente</h3>
                <p class="text-gray-600" id="customerName"></p>
            </div>
            
            <!-- Order Items -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Itens do Pedido</h2>
                <div id="orderItems" class="space-y-4">
                    <!-- Items will be populated by JavaScript -->
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Resumo do Pedido</h3>
                        <p class="text-gray-600" id="itemsCount"></p>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-bold text-indigo-600" id="orderTotalSummary"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-modal>

<script>
function showOrderDetails(orderId) {
    // Show modal and loading
    openModal('orderDetailsModal');
    document.getElementById('loadingSpinner').classList.remove('hidden');
    document.getElementById('errorMessage').classList.add('hidden');
    document.getElementById('orderDetails').classList.add('hidden');
    
    // Fetch order details
    fetch(`/orders/${orderId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateOrderDetails(data.order);
            } else {
                showError();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError();
        });
}

function populateOrderDetails(order) {
    // Hide loading, show content
    document.getElementById('loadingSpinner').classList.add('hidden');
    document.getElementById('orderDetails').classList.remove('hidden');
    
    // Populate basic info
    document.getElementById('orderDate').textContent = order.created_at;
    document.getElementById('orderTotal').textContent = `R$ ${formatCurrency(order.total)}`;
    document.getElementById('orderTotalSummary').textContent = `R$ ${formatCurrency(order.total)}`;
    document.getElementById('itemsCount').textContent = `${order.order_items.length} ${order.order_items.length === 1 ? 'item' : 'itens'}`;
    
    // Populate status
    const statusElement = document.getElementById('orderStatus');
    statusElement.textContent = order.status_label;
    statusElement.className = `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getStatusClasses(order.status)}`;
    
    // Show customer info for admin
    @auth
        @if(auth()->user()->isAdmin())
            document.getElementById('customerInfo').classList.remove('hidden');
            document.getElementById('customerName').textContent = order.user_name;
        @endif
    @endauth
    
    // Populate order items
    const itemsContainer = document.getElementById('orderItems');
    itemsContainer.innerHTML = '';
    
    order.order_items.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.className = 'flex items-center justify-between border border-gray-200 rounded-lg p-4';
        itemElement.innerHTML = `
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                    ${item.product_name.substring(0, 2).toUpperCase()}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">${item.product_name}</h3>
                    <p class="text-gray-600">Preço unitário: R$ ${formatCurrency(item.unit_price)}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="mb-2">
                    <span class="text-lg font-bold text-gray-900">Qtd: ${item.quantity}</span>
                </div>
                <div>
                    <span class="text-xl font-bold text-indigo-600">
                        R$ ${formatCurrency(item.total_price)}
                    </span>
                </div>
            </div>
        `;
        itemsContainer.appendChild(itemElement);
    });
}

function showError() {
    document.getElementById('loadingSpinner').classList.add('hidden');
    document.getElementById('errorMessage').classList.remove('hidden');
}

function formatCurrency(value) {
    return parseFloat(value).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function getStatusClasses(status) {
    switch(status) {
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'paid':
            return 'bg-green-100 text-green-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}
</script>
