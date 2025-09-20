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
                       class="flex items-center px-4 py-3 text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user w-5 h-5 mr-3"></i>
                        <span class="font-medium">Perfil</span>
                    </a>
                    <a href="{{ route('settings.password') }}" 
                       class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg">
                        <i class="fas fa-lock w-5 h-5 mr-3"></i>
                        <span class="font-medium">Senha</span>
                    </a>
                    <a href="{{ route('settings.appearance') }}" 
                       class="flex items-center px-4 py-3 text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-palette w-5 h-5 mr-3"></i>
                        <span class="font-medium">Aparência</span>
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Alterar Senha</h2>
                        <p class="text-gray-600 mt-1">Certifique-se de que sua conta está usando uma senha longa e aleatória para se manter segura</p>
                    </div>

                    <form wire:submit="updatePassword" class="space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Senha Atual
                            </label>
                            <input 
                                type="password" 
                                id="current_password"
                                wire:model="current_password" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                required 
                                autocomplete="current-password"
                            >
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nova Senha
                            </label>
                            <input 
                                type="password" 
                                id="password"
                                wire:model="password" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                required 
                                autocomplete="new-password"
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Nova Senha
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation"
                                wire:model="password_confirmation" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                required 
                                autocomplete="new-password"
                            >
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Save Button -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <div class="flex items-center">
                                <x-action-message class="text-green-600" on="password-updated">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Senha atualizada com sucesso!
                                </x-action-message>
                            </div>
                            <button 
                                type="submit" 
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center"
                            >
                                <i class="fas fa-save mr-2"></i>
                                Atualizar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>