<x-layouts.app :title="__('Editar Usuário')">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Editar Usuário</h1>
                        <p class="text-gray-600 mt-1">Atualize as informações do usuário</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('users.show', $user) }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-eye mr-2"></i>
                            Ver Detalhes
                        </a>
                        <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome Completo *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            placeholder="Digite o nome completo"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                            placeholder="usuario@exemplo.com"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Senha -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nova Senha
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                            placeholder="Deixe em branco para manter a senha atual"
                        >
                        <p class="mt-1 text-sm text-gray-500">Deixe em branco para manter a senha atual</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Nova Senha
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Confirme a nova senha"
                        >
                    </div>
                </div>

                <!-- Perfil -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Perfil *
                    </label>
                    <select 
                        id="role" 
                        name="role" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('role') border-red-500 @enderror"
                    >
                        <option value="">Selecione um perfil</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Cliente</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informações atuais -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Informações Atuais</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Criado em:</span>
                            <span class="ml-2 text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Última atualização:</span>
                            <span class="ml-2 text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('users.show', $user) }}" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
