<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Configurações</h1>
        <p class="text-gray-600 mt-2">Gerencie suas informações de perfil e configurações da conta</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Settings Navigation -->
        <div class="lg:col-span-1">
            <nav class="space-y-2">
                <a href="{{ route('settings.profile') }}" 
                   class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg">
                    <i class="fas fa-user w-5 h-5 mr-3"></i>
                    <span class="font-medium">Perfil</span>
                </a>
                <a href="{{ route('settings.password') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-lock w-5 h-5 mr-3"></i>
                    <span class="font-medium">Senha</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Informações do Perfil</h2>
                    <p class="text-gray-600 mt-1">Atualize seu nome e endereço de email</p>
                </div>

                <form wire:submit="updateProfileInformation" class="space-y-6">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome
                        </label>
                        <input 
                            type="text" 
                            id="name"
                            wire:model="name" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            required 
                            autofocus 
                            autocomplete="name"
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email"
                            wire:model="email" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            required 
                            autocomplete="email"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-3"></i>
                                    <div>
                                        <p class="text-sm text-yellow-800">
                                            Seu endereço de email não foi verificado.
                                        </p>
                                        <button 
                                            type="button"
                                            wire:click.prevent="resendVerificationNotification"
                                            class="text-sm text-yellow-600 hover:text-yellow-700 underline mt-1"
                                        >
                                            Clique aqui para reenviar o email de verificação.
                                        </button>
                                    </div>
                                </div>

                                @if (session('status') === 'verification-link-sent')
                                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <p class="text-sm text-green-800 font-medium">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Um novo link de verificação foi enviado para seu endereço de email.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Save Button -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex items-center">
                            <x-action-message class="text-green-600" on="profile-updated">
                                <i class="fas fa-check-circle mr-2"></i>
                                Salvo com sucesso!
                            </x-action-message>
                        </div>
                        <button 
                            type="submit" 
                            class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center"
                        >
                            <i class="fas fa-save mr-2"></i>
                            Salvar Alterações
                        </button>
                    </div>
                </form>

                <!-- Delete Account Section -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-600 mt-1 mr-3"></i>
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-red-900 mb-2">Zona de Perigo</h3>
                                <p class="text-sm text-red-700 mb-4">
                                    Uma vez que sua conta seja excluída, todos os seus recursos e dados serão permanentemente excluídos. 
                                    Antes de excluir sua conta, baixe todos os dados ou informações que deseja reter.
                                </p>
                                <livewire:settings.delete-user-form />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>