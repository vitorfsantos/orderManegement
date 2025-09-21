<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="w-full max-w-4xl">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="flex flex-col lg:flex-row min-h-[600px]">
                <!-- Lado Esquerdo - Informações com Degradê -->
                <div class="lg:w-1/2 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 p-8 lg:p-12 flex flex-col justify-center text-white relative overflow-hidden">
                    <!-- Elementos decorativos -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full translate-y-12 -translate-x-12"></div>
                    
                    <div class="relative z-10">
                        <div class="mb-8">
                            <h1 class="text-4xl font-bold mb-4">Bem-vindo de volta!</h1>
                            <p class="text-blue-100 text-lg leading-relaxed">
                                Gerencie seus pedidos de forma eficiente e organizada. 
                                Acesse sua conta para continuar.
                            </p>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shopping-cart text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Gestão de Pedidos</h3>
                                    <p class="text-blue-100 text-sm">Controle total sobre seus pedidos</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-chart-line text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Relatórios</h3>
                                    <p class="text-blue-100 text-sm">Acompanhe métricas importantes</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">Equipe</h3>
                                    <p class="text-blue-100 text-sm">Colabore com sua equipe</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Lado Direito - Formulário de Login -->
                <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                    <div class="max-w-md mx-auto w-full">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">Entrar</h2>
                            <p class="text-gray-600">Digite suas credenciais para acessar sua conta</p>
                        </div>
                        
                        <!-- Informações de Teste -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <h3 class="text-sm font-medium text-blue-800 mb-2 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Usuários de Teste:
                            </h3>
                            <div class="text-xs text-blue-700 space-y-1">
                                <p><strong>Admin:</strong> admin@example.com / password</p>
                                <p><strong>Cliente:</strong> cliente@example.com / password</p>
                            </div>
                        </div>

                        <form class="space-y-6" method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        E-mail
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400"></i>
                                        </div>
                                        <input id="email" name="email" type="email" autocomplete="email" required 
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                                               placeholder="seu@email.com" value="{{ old('email') }}">
                                    </div>
                                   
                                </div>
                                
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Senha
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-lock text-gray-400"></i>
                                        </div>
                                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                                               placeholder="Sua senha">
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                            @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror

                            <div>
                                <button type="submit" 
                                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Entrar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
