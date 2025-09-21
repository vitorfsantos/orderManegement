<x-layouts.app :title="__('Usuários')">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Usuários</h1>
                    <p class="text-gray-600 mt-1">Gerencie os usuários do sistema</p>
                </div>
                <div>
                    @if(auth()->user()->isAdmin())
                        <button onclick="openModal('create-user-modal')" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Novo Usuário
                        </button>
                    @else
                        <div class="text-red-600 text-sm">
                            Você precisa ser administrador para criar usuários.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Filtros -->
            <div class="mb-6 flex flex-col sm:flex-row gap-4">
                <form method="GET" class="flex-1">
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Buscar por nome ou email..."
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <div class="flex gap-2">
                    <a href="{{ route('users.index') }}" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 {{ !$role ? 'bg-blue-50 border-blue-300 text-blue-700' : 'text-gray-700' }}">
                        Todos
                    </a>
                    <a href="{{ route('users.index', ['role' => 'admin']) }}" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 {{ $role === 'admin' ? 'bg-blue-50 border-blue-300 text-blue-700' : 'text-gray-700' }}">
                        Administradores
                    </a>
                    <a href="{{ route('users.index', ['role' => 'customer']) }}" class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 {{ $role === 'customer' ? 'bg-blue-50 border-blue-300 text-blue-700' : 'text-gray-700' }}">
                        Clientes
                    </a>
                </div>
            </div>
            
            <!-- Lista de usuários -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Usuário</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Perfil</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Criado em</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            {{ $user->initials() }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-gray-600">{{ $user->email }}</td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $user->isAdmin() ? 'Administrador' : 'Cliente' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-gray-600">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button onclick="showUser('{{ $user->id }}')" class="text-blue-600 hover:text-blue-800" title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button onclick="editUser('{{ $user->id }}')" class="text-indigo-600 hover:text-indigo-800" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <button onclick="deleteUser('{{ $user->id }}', '{{ $user->name }}')" class="text-red-600 hover:text-red-800" title="Remover">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-users text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum usuário encontrado</h3>
                                    <p class="text-gray-500 mb-4">
                                        @if($search)
                                            Nenhum usuário encontrado para "{{ $search }}"
                                        @else
                                            Comece adicionando o primeiro usuário ao sistema.
                                        @endif
                                    </p>
                                    <button onclick="openModal('create-user-modal')" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                                        <i class="fas fa-plus mr-2"></i>
                                        Adicionar Usuário
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="mt-8">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    @include('Users.modals.create')
    @include('Users.modals.show')
    @include('Users.modals.edit')

    <!-- JavaScript -->
    <script src="{{ asset('js/users.js') }}"></script>
</x-layouts.app>
