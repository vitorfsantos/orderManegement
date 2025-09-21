<!-- Cancel Order Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCancelModal()"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="cancelModalTitle">
                            Cancelar Pedido
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="cancelModalMessage">
                                Tem certeza que deseja cancelar este pedido?
                            </p>
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-700">Pedido:</span>
                                    <span class="text-sm font-semibold text-gray-900" id="cancelOrderId">#123</span>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-sm font-medium text-gray-700">Valor Total:</span>
                                    <span class="text-lg font-bold text-red-600" id="cancelOrderTotal">R$ 0,00</span>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-sm font-medium text-gray-700">Cliente:</span>
                                    <span class="text-sm text-gray-900" id="cancelCustomerName">Nome do Cliente</span>
                                </div>
                            </div>
                            <div class="mt-4 p-3 bg-red-50 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-2"></i>
                                    <div class="text-sm text-red-700">
                                        <p class="font-medium">Ao cancelar o pedido:</p>
                                        <ul class="mt-1 list-disc list-inside space-y-1">
                                            <li>O status do pedido será alterado para "Cancelado"</li>
                                            <li>As quantidades dos produtos serão devolvidas ao estoque</li>
                                            <li>Esta ação não pode ser desfeita</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="cancelConfirmationForm" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Sim, Cancelar Pedido
                    </button>
                </form>
                <button type="button" 
                        onclick="closeCancelModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Manter Pedido
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showCancelModal(orderId, orderTotal, customerName) {
    // Preencher os dados do modal
    document.getElementById('cancelOrderId').textContent = '#' + orderId;
    document.getElementById('cancelOrderTotal').textContent = 'R$ ' + orderTotal;
    document.getElementById('cancelCustomerName').textContent = customerName;
    
    // Configurar o formulário
    const form = document.getElementById('cancelConfirmationForm');
    form.action = `/orders/${orderId}/status`;
    
    // Mostrar o modal
    document.getElementById('cancelModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Fechar modal com ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeCancelModal();
    }
});
</script>
