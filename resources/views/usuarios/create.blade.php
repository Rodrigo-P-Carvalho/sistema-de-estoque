<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Usuário - AutoPeças</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex h-screen overflow-hidden font-sans">

    <!-- BARRA LATERAL (SIDEBAR) -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col shadow-sm">
        <div class="h-16 flex items-center px-6 border-b border-slate-200">
            <h1 class="text-2xl font-bold text-blue-600">Auto<span class="text-slate-800">Peças</span></h1>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Link Dashboard (Inativo) -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-slate-900 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <!-- Link Usuários (ATIVO - Marcado de azul) -->
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-blue-50 text-blue-600 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Usuários
            </a>
        </nav>

        <div class="p-4 border-t border-slate-200">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg font-medium transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sair do Sistema
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-800">Gestão de Usuários</h2>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-500">Logado como:</span>
                <span class="text-sm font-medium text-slate-700 bg-slate-100 px-3 py-1 rounded-full">
                    {{ auth()->user()->name }}
                </span>
            </div>
        </header>

        <div class="p-8">
            <!-- Cabeçalho da Página -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Novo Usuário</h3>
                    <p class="text-slate-500 mt-1">Cadastre um novo membro da equipe para acessar o estoque.</p>
                </div>
                <!-- Botão Voltar -->
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 px-4 py-2 rounded-lg transition-colors shadow-sm">
                    Voltar
                </a>
            </div>

            <!-- Card do Formulário -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 max-w-2xl">
                
                <!-- Coloquei action="#" por enquanto, para não dar erro ao testar o layout -->
                <form action="{{ route('usuarios.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nome -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nome Completo</label>
                            <input type="text" id="name" name="name" required placeholder="Ex: Maria da Silva" 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-slate-800 text-sm">
                        </div>
                        
                        <!-- Email -->
                        <div class="md:col-span-1">
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-mail corporativo</label>
                            <input type="email" id="email" name="email" required placeholder="maria@estoque.com" 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-slate-800 text-sm">
                        </div>

                        <!-- Perfil -->
                        <div class="md:col-span-1">
                            <label for="perfil_id" class="block text-sm font-medium text-slate-700 mb-1">Perfil de Acesso</label>
                            <!-- Os options aqui depois virão do banco de dados dinamicamente -->
                            <select id="perfil_id" name="perfil_id" required 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-slate-800 text-sm bg-white">
                                <option value="" disabled selected>Selecione um perfil...</option>
                                @foreach($perfis as $perfil)
                                    <option value="{{ $perfil->id }}">{{ $perfil->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Alerta Informativo sobre a Senha -->
                    <div class="bg-blue-50 border border-blue-100 text-blue-800 p-4 rounded-lg flex items-start gap-3 text-sm">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p>Uma senha temporária será gerada automaticamente. O usuário receberá um e-mail com o link de acesso e instruções para cadastrar sua senha definitiva.</p>
                    </div>

                    <!-- Footer com Botões -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors cursor-pointer shadow-sm">
                            Cadastrar e Enviar Convite
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>
</html>