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
                       class="flex items-center px-4 py-3 text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-lock w-5 h-5 mr-3"></i>
                        <span class="font-medium">Senha</span>
                    </a>
                    <a href="{{ route('settings.appearance') }}" 
                       class="flex items-center px-4 py-3 text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg">
                        <i class="fas fa-palette w-5 h-5 mr-3"></i>
                        <span class="font-medium">Aparência</span>
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Preferências de Aparência</h2>
                        <p class="text-gray-600 mt-1">Personalize a aparência da sua interface</p>
                    </div>

                    <div class="space-y-8">
                        <!-- Theme Selection -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Tema</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Light Theme -->
                                <div class="relative">
                                    <input type="radio" id="light" name="theme" value="light" class="sr-only" checked>
                                    <label for="light" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900">Claro</span>
                                            <i class="fas fa-sun text-yellow-500"></i>
                                        </div>
                                        <p class="text-sm text-gray-600">Interface clara e limpa</p>
                                    </label>
                                </div>

                                <!-- Dark Theme -->
                                <div class="relative">
                                    <input type="radio" id="dark" name="theme" value="dark" class="sr-only">
                                    <label for="dark" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900">Escuro</span>
                                            <i class="fas fa-moon text-indigo-500"></i>
                                        </div>
                                        <p class="text-sm text-gray-600">Interface escura e elegante</p>
                                    </label>
                                </div>

                                <!-- System Theme -->
                                <div class="relative">
                                    <input type="radio" id="system" name="theme" value="system" class="sr-only">
                                    <label for="system" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-medium text-gray-900">Sistema</span>
                                            <i class="fas fa-desktop text-gray-500"></i>
                                        </div>
                                        <p class="text-sm text-gray-600">Segue as configurações do sistema</p>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Language Selection -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Idioma</h3>
                            <div class="max-w-md">
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="pt-BR" selected>Português (Brasil)</option>
                                    <option value="en">English</option>
                                    <option value="es">Español</option>
                                </select>
                            </div>
                        </div>

                        <!-- Color Scheme -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Esquema de Cores</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="relative">
                                    <input type="radio" id="blue" name="color" value="blue" class="sr-only" checked>
                                    <label for="blue" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition-colors">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full mx-auto mb-2"></div>
                                        <span class="text-sm font-medium text-gray-900">Azul</span>
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="radio" id="green" name="color" value="green" class="sr-only">
                                    <label for="green" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition-colors">
                                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-full mx-auto mb-2"></div>
                                        <span class="text-sm font-medium text-gray-900">Verde</span>
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="radio" id="purple" name="color" value="purple" class="sr-only">
                                    <label for="purple" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition-colors">
                                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full mx-auto mb-2"></div>
                                        <span class="text-sm font-medium text-gray-900">Roxo</span>
                                    </label>
                                </div>

                                <div class="relative">
                                    <input type="radio" id="orange" name="color" value="orange" class="sr-only">
                                    <label for="orange" class="block p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-300 transition-colors">
                                        <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full mx-auto mb-2"></div>
                                        <span class="text-sm font-medium text-gray-900">Laranja</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="flex items-center justify-end pt-6 border-t border-gray-200">
                            <button 
                                type="button" 
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 flex items-center"
                            >
                                <i class="fas fa-save mr-2"></i>
                                Salvar Preferências
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>