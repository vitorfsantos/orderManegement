<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informações principais -->
    <div class="lg:col-span-2">
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Pessoais</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nome Completo</label>
                    <p class="mt-1 text-gray-900">{{ $user->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Perfil</label>
                    <div class="mt-1">
                        <span class="px-3 py-1 text-sm rounded-full {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                            {{ $user->isAdmin() ? 'Administrador' : 'Cliente' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Avatar e informações adicionais -->
    <div class="space-y-6">
        <!-- Avatar -->
        <div class="bg-gray-50 rounded-lg p-6 text-center">
            <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                {{ $user->initials() }}
            </div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
            <p class="text-gray-600">{{ $user->email }}</p>
        </div>

        <!-- Informações do sistema -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Sistema</h3>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">ID do Usuário</label>
                    <p class="mt-1 text-sm text-gray-600 font-mono">{{ $user->id }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Criado em</label>
                    <p class="mt-1 text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Última atualização</label>
                    <p class="mt-1 text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                
                @if($user->email_verified_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email verificado</label>
                        <p class="mt-1 text-green-600 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ $user->email_verified_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email verificado</label>
                        <p class="mt-1 text-red-600 flex items-center">
                            <i class="fas fa-times-circle mr-2"></i>
                            Não verificado
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Ações -->
        <div class="bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações</h3>
            
            <div class="space-y-3">
                <button onclick="editUser('{{ $user->id }}')" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Usuário
                </button>
                
                @if($user->id !== auth()->id())
                    <button onclick="deleteUser('{{ $user->id }}', '{{ $user->name }}')" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>
                        Remover Usuário
                    </button>
                @else
                    <div class="w-full bg-gray-300 text-gray-500 px-4 py-2 rounded-lg flex items-center justify-center cursor-not-allowed">
                        <i class="fas fa-lock mr-2"></i>
                        Não é possível remover sua própria conta
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
