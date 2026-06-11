<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Estoque</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">AutoPeças</h1>
            <p class="text-slate-500 text-sm">Entre com suas credenciais para acessar o estoque</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative mb-5 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin=\"round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="font-medium text-xs md:text-sm">E-mail ou senha incorretos.</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf 

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="seu@email.com"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-slate-800 text-sm">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-sm font-medium text-slate-700">Senha</label>
                    <a href="#" class="text-xs text-blue-600 hover:underline">Esqueceu a senha?</a>
                </div>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-slate-800 text-sm">
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition duration-200 text-sm cursor-pointer shadow-sm">
                Entrar no Sistema
            </button>
        </form>

    </div>

</body>
</html>