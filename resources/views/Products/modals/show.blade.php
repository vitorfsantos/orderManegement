<x-modal id="show-product-modal" title="Detalhes do Produto" size="lg">
    <div id="product-details-content">
        <!-- Content will be loaded here -->
    </div>
</x-modal>

<script>
function showProduct(productId) {
    fetch(`/admin/products/${productId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('product-details-content').innerHTML = data.html;
            openModal('show-product-modal');
        } else {
            showError('Erro ao carregar detalhes do produto.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Erro ao carregar detalhes do produto.');
    });
}
</script>
