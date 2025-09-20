<x-modal id="create-user-modal" title="Novo Usuário" size="lg">
    <form id="create-user-form" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
                <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome Completo *
                </label>
                <input 
                    type="text" 
                    id="create_name" 
                    name="name" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Digite o nome completo"
                >
                <div id="create_name_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>

            <!-- Email -->
            <div>
                <label for="create_email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email *
                </label>
                <input 
                    type="email" 
                    id="create_email" 
                    name="email" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="usuario@exemplo.com"
                >
                <div id="create_email_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Senha -->
            <div>
                <label for="create_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Senha *
                </label>
                <input 
                    type="password" 
                    id="create_password" 
                    name="password" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Mínimo 8 caracteres"
                >
                <div id="create_password_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>

            <!-- Confirmar Senha -->
            <div>
                <label for="create_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmar Senha *
                </label>
                <input 
                    type="password" 
                    id="create_password_confirmation" 
                    name="password_confirmation" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Confirme a senha"
                >
                <div id="create_password_confirmation_error" class="mt-1 text-sm text-red-600 hidden"></div>
            </div>
        </div>

        <!-- Perfil -->
        <div>
            <label for="create_role" class="block text-sm font-medium text-gray-700 mb-2">
                Perfil *
            </label>
            <select 
                id="create_role" 
                name="role" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">Selecione um perfil</option>
                <option value="admin">Administrador</option>
                <option value="customer">Cliente</option>
            </select>
            <div id="create_role_error" class="mt-1 text-sm text-red-600 hidden"></div>
        </div>
    </form>

    <x-slot name="footer">
        <button type="button" onclick="closeModal('create-user-modal')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            Cancelar
        </button>
        <button type="button" onclick="submitCreateUser()" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
            <i class="fas fa-save mr-2"></i>
            Criar Usuário
        </button>
    </x-slot>
</x-modal>

<script>
function submitCreateUser() {
    const form = document.getElementById('create-user-form');
    const formData = new FormData(form);
    
    // Clear previous errors
    clearErrors('create_');
    
    fetch('{{ route("users.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('create-user-modal');
            form.reset();
            showSuccess(data.message);
            // Reload the page to show the new user
            window.location.reload();
        } else {
            showErrors(data.errors, 'create_');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Erro ao criar usuário. Tente novamente.');
    });
}
</script>
