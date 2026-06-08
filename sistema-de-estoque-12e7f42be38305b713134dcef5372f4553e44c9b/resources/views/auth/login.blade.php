<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Estoque</title>
    <!-- Tailwind CSS para estilização rápida -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">

    <!-- Container Centralizado -->
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        
        <!-- Cabeçalho do Card -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">AutoPeças</h1>
            <p class="text-slate-500 text-sm">Entre com suas credenciais para acessar o estoque</p>
        </div>

        <!-- Formulário de Login -->
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            <!-- Diretiva de segurança OBRIGATÓRIA do Laravel -->
            @csrf 

            <!-- Campo E-mail -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mail</label>
                <input type="email" id="email" name="email" required autocomplete="email" placeholder="seu@email.com"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-slate-800 text-sm">
            </div>

            <!-- Campo Senha -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-sm font-medium text-slate-700">Senha</label>
                    <a href="#" class="text-xs text-blue-600 hover:underline">Esqueceu a senha?</a>
                </div>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-slate-800 text-sm">
            </div>

            <!-- Botão de Entrar -->
            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition duration-200 text-sm cursor-pointer shadow-sm">
                Entrar no Sistema
            </button>
        </form>

    </div>

</body>
</html>