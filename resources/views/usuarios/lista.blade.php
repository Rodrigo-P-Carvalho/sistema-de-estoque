<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários - AutoPeças</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-8 font-sans">

    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Lista de Usuários</h2>
                <p class="text-slate-500 text-sm mt-1">Gerencie todos os acessos ao sistema.</p>
            </div>
            <a href="{{ route('usuarios.index') }}" class="flex items-center gap-2 text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 px-4 py-2 rounded-lg font-medium transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Voltar ao Painel
            </a>
        </div>

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
                                    <a href="#" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Editar
                                    </a>
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

</body>
</html>