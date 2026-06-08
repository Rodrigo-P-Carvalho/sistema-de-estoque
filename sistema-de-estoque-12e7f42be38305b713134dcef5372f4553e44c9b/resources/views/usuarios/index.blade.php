@extends('layouts.app')

@section('titulo_pagina', 'Usuários')

@section('conteudo')

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto">

        <div class="p-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Painel de Usuários</h3>
                    <p class="text-slate-500 mt-1">Escolha uma ação abaixo para gerenciar os acessos ao sistema.</p>
                </div>
            </div>

            <!-- GRID COM OS 3 BOTÕES/CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Card 1: Criar Usuário (Link Ativo) -->
                <a href="{{ route('usuarios.create') }}" class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-blue-500 hover:shadow-md transition-all flex flex-col items-center text-center">
                    <div class="p-4 bg-blue-50 text-blue-600 rounded-full mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-1">Novo Usuário</h4>
                    <p class="text-sm text-slate-500">Cadastre um novo membro e envie um convite de acesso.</p>
                </a>

                <!-- Card 2: Lista de Usuários (Apenas o visual, sem link ainda) -->
                <a href="{{ route('usuarios.lista') }}" class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-blue-500 hover:shadow-md transition-all flex flex-col items-center text-center">
                    <div class="p-4 bg-indigo-50 text-indigo-600 rounded-full mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-1">Lista de Usuários</h4>
                    <p class="text-sm text-slate-500">Visualize todos os usuários ativos e inativos no sistema.</p>
                </a>

                <!-- Card 3: Alterar Dados (Apenas o visual, sem link ainda) -->
                <a href="#" class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-blue-500 hover:shadow-md transition-all flex flex-col items-center text-center">
                    <div class="p-4 bg-orange-50 text-orange-600 rounded-full mb-4 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-1">Alterar Dados</h4>
                    <p class="text-sm text-slate-500">Edite informações, mude perfis ou redefina senhas.</p>
                </a>

            </div>
        </div>
    </main>

</body>
</html>
@endsection