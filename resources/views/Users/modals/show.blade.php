<x-modal id="show-user-modal" title="Detalhes do Usuário" size="lg">
    <div id="user-details-content">
        <!-- Content will be loaded here -->
    </div>
</x-modal>

<script>
function showUser(userId) {
    fetch(`/users/${userId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('user-details-content').innerHTML = data.html;
            openModal('show-user-modal');
        } else {
            showError('Erro ao carregar detalhes do usuário.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Erro ao carregar detalhes do usuário.');
    });
}
</script>
