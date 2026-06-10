@extends('layouts.app')

@section('titulo_pagina', 'Usuários')

@section('conteudo')
<body class="bg-slate-50 min-h-screen p-8 font-sans">

    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Lista de Usuários</h2>
                <p class="text-slate-500 text-sm mt-1">Gerencie todos os acessos ao sistema.</p>
            </div>
            <a href="{{ route('administracao.index') }}" class="flex items-center gap-2 text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 px-4 py-2 rounded-lg font-medium transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar ao painel de Usuário
            </a>
        </div>

        @if(session('sucesso'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg relative mb-6 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-medium text-sm">{{ session('sucesso') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="GET" action="{{ route('usuarios.lista') }}" class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 flex flex-col md:flex-row gap-4 items-end">
            
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-slate-700 mb-1">Buscar Usuário</label>
                <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Nome ou e-mail..." 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="w-full md:w-64">
                <label class="block text-sm font-medium text-slate-700 mb-1">Filtrar por Perfil</label>
                <select name="perfil_id" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="">Todos os perfis</option>
                    @foreach($perfis as $perfil)
                        <option value="{{ $perfil->id }}" {{ request('perfil_id') == $perfil->id ? 'selected' : '' }}>
                            {{ $perfil->descricao }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition-colors shadow-sm w-full md:w-auto">
                    Pesquisar
                </button>
                @if(request('busca') || request('perfil_id'))
                    <a href="{{ route('usuarios.lista') }}" class="flex items-center justify-center text-slate-600 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-lg font-medium transition-colors border border-slate-200">
                        Limpar
                    </a>
                @endif
            </div>
        </form>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-800 font-medium border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-4">Nome</th>
                            <th scope="col" class="px-6 py-4">E-mail</th>
                            <th scope="col" class="px-6 py-4">Perfil</th>
                            <th scope="col" class="px-6 py-4">Status</th>
                            <th scope="col" class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        
                        @forelse($usuarios as $usuario)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $usuario->name }}</td>
                                <td class="px-6 py-4">{{ $usuario->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $usuario->perfil->descricao ?? 'Sem perfil' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($usuario->primeiro_acesso)
                                        <span class="text-amber-600 flex items-center gap-1"><span class="w-2 h-2 bg-amber-500 rounded-full"></span> Pendente</span>
                                    @else
                                        <span class="text-emerald-600 flex items-center gap-1"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Ativo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button type="button" onclick="abrirModalUsuario({{ $usuario->id }})" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    Nenhum usuário encontrado na pesquisa.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $usuarios->links() }}
            </div>
        </div>

    </div>

    <div id="modalUsuario" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex justify-center items-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                <h4 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Editar Cadastro do Usuário
                </h4>
                <button type="button" onclick="fecharModalUsuario()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l18 18"></path></svg>
                </button>
            </div>
            
            <form id="formUsuario" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nome Completo</label>
                        <input type="text" name="edit_name" id="edit_name" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Endereço de E-mail</label>
                        <input type="email" name="edit_email" id="edit_email" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Perfil de Acesso</label>
                        <select name="edit_perfil_id" id="edit_perfil_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all bg-white" required>
                            <option value="">Selecione um perfil...</option>
                            @foreach($perfis as $perfil)
                                <option value="{{ $perfil->id }}">{{ $perfil->descricao }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="fecharModalUsuario()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalUsuario(id) {
            // 1. Busca os dados do usuário na rota correta
            fetch(`/administracao/usuarios/${id}/editar`)
                .then(response => {
                    if (!response.ok) throw new Error();
                    return response.json();
                })
                .then(data => {
                    document.getElementById('edit_name').value = data.name || '';
                    document.getElementById('edit_email').value = data.email || '';
                    document.getElementById('edit_perfil_id').value = data.perfil_id || '';

                    document.getElementById('formUsuario').action = `/administracao/usuarios/${id}`;

                    document.getElementById('modalUsuario').classList.remove('hidden');
                })
                .catch(error => alert('Erro técnico ao tentar resgatar os dados do usuário.'));
        }

        function fecharModalUsuario() {
            document.getElementById('modalUsuario').classList.add('hidden');
        }
    </script>

</body>
</html>
@endsection