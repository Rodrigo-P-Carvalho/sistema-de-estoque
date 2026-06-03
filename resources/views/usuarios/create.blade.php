@extends('layouts.app')

@section('titulo_pagina', 'Usuários')

@section('conteudo')

        <div class="p-8">
            <!-- Cabeçalho da Página -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Novo Usuário</h3>
                    <p class="text-slate-500 mt-1">Cadastre um novo membro da equipe para acessar o estoque.</p>
                </div>
                <!-- Botão Voltar -->
                <a href="{{ route('usuarios.index') }}" class="flex items-center gap-2 text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 px-4 py-2 rounded-lg font-medium transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar ao painel de Usuário
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
@endsection