<x-modal id="edit-user-modal" title="Editar Usuário" size="lg">
    <form id="edit-user-form" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" id="edit_user_id" name="user_id">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome Completo *
                </label>
                <input 
                    type="text" 
                    id="edit_name" 
                    name="name" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Digite o nome completo"
                >
                <div id="edit_name_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>

            <!-- Email -->
            <div>
                <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email *
                </label>
                <input 
                    type="email" 
                    id="edit_email" 
                    name="email" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="usuario@exemplo.com"
                >
                <div id="edit_email_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Senha -->
            <div>
                <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Nova Senha
                </label>
                <input 
                    type="password" 
                    id="edit_password" 
                    name="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Deixe em branco para manter a senha atual"
                >
                <p class="mt-1 text-sm text-gray-500">Deixe em branco para manter a senha atual</p>
                <div id="edit_password_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>

            <!-- Confirmar Senha -->
            <div>
                <label for="edit_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmar Nova Senha
                </label>
                <input 
                    type="password" 
                    id="edit_password_confirmation" 
                    name="password_confirmation"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Confirme a nova senha"
                >
                <div id="edit_password_confirmation_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>
        </div>

        <!-- Perfil -->
        <div>
            <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-2">
                Perfil *
            </label>
            <select 
                id="edit_role" 
                name="role" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">Selecione um perfil</option>
                <option value="admin">Administrador</option>
                <option value="customer">Cliente</option>
            </select>
            <div id="edit_role_error" class="mt-1 text-sm text-red-600 hidden"></div>
        </div>

        <!-- Informações atuais -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Informações Atuais</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-600">Criado em:</span>
                    <span id="edit_created_at" class="ml-2 text-gray-900"></span>
                </div>
                <div>
                    <span class="text-gray-600">Última atualização:</span>
                    <span id="edit_updated_at" class="ml-2 text-gray-900"></span>
                </div>
            </div>
        </div>
    </form>

    <x-slot name="footer">
        <button type="button" onclick="closeModal('edit-user-modal')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            Cancelar
        </button>
        <button type="button" onclick="submitEditUser()" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
            <i class="fas fa-save mr-2"></i>
            Salvar Alterações
        </button>
    </x-slot>
</x-modal>

<script>
function editUser(userId) {
    fetch(`/users/${userId}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Populate form with user data
            document.getElementById('edit_user_id').value = data.user.id;
            document.getElementById('edit_name').value = data.user.name;
            document.getElementById('edit_email').value = data.user.email;
            document.getElementById('edit_role').value = data.user.role;
            document.getElementById('edit_created_at').textContent = data.user.created_at;
            document.getElementById('edit_updated_at').textContent = data.user.updated_at;
            
            openModal('edit-user-modal');
        } else {
            showError('Erro ao carregar dados do usuário.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Erro ao carregar dados do usuário.');
    });
}

function submitEditUser() {
    const userId = document.getElementById('edit_user_id').value;
    const form = document.getElementById('edit-user-form');
    const formData = new FormData(form);
    
    // Clear previous errors
    clearErrors('edit_');
    
    fetch(`/users/${userId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-HTTP-Method-Override': 'PUT',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('edit-user-modal');
            showSuccess(data.message);
            // Reload the page to show updated data
            window.location.reload();
        } else {
            showErrors(data.errors, 'edit_');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Erro ao atualizar usuário. Tente novamente.');
    });
}
</script>
