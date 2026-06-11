@extends('layouts.app')

@section('titulo_pagina', 'Gerenciar Perfis')

@section('conteudo')

<div class="p-8 w-full max-w-7xl mx-auto">
    
    <div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">Gerenciar Perfis de Acesso</h3>
            <p class="text-slate-500 mt-1">Crie funções e atribua permissões específicas para o sistema.</p>
        </div>
        
        <a href="{{ route('administracao.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600 hover:border-blue-300 transition-all shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar para Administração
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h4 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Novo Perfil</h4>
                
                <form action="{{ route('perfis.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nome do Perfil</label>
                        <input type="text" name="descricao" value="{{ old('descricao') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div x-data="{ 
                        open: false, 
                        selecoes: @json(old('permissoes', [])), 
                        labels: {
                            'administracao': 'Administração',
                            'produtos': 'Produtos',
                            'vendas': 'Vendas',
                            'compras': 'Compras'
                        }
                    }" class="relative">
                        
                        <label class="block text-sm font-medium text-slate-700 mb-1">Permissões de Acesso</label>
                        
                        <button @click="open = !open" type="button" class="w-full bg-white border border-slate-300 px-3 py-2 rounded-lg text-left text-slate-700 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500 min-h-[42px]">
                            
                            <span x-show="selecoes.length === 0" class="text-slate-500">Selecionar módulos...</span>
                            
                            <div x-show="selecoes.length > 0" class="flex flex-wrap gap-1">
                                <template x-for="item in selecoes" :key="item">
                                    <span class="px-2 py-1 bg-blue-100 border border-blue-200 text-blue-700 rounded text-xs font-semibold" x-text="labels[item]"></span>
                                </template>
                            </div>

                            <svg class="w-4 h-4 text-slate-500 transition-transform duration-200 ml-2 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak x-transition class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                            <div class="p-2 space-y-1 text-sm">
                                <label class="flex items-center gap-2 p-2 hover:bg-slate-50 rounded cursor-pointer">
                                    <input type="checkbox" name="permissoes[]" value="administracao" x-model="selecoes" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span class="text-slate-700">Administração (Usuários e Fornecedores)</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 hover:bg-slate-50 rounded cursor-pointer">
                                    <input type="checkbox" name="permissoes[]" value="produtos" x-model="selecoes" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span class="text-slate-700">Módulo de Produtos</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 hover:bg-slate-50 rounded cursor-pointer">
                                    <input type="checkbox" name="permissoes[]" value="vendas" x-model="selecoes" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span class="text-slate-700">Módulo de Vendas</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 hover:bg-slate-50 rounded cursor-pointer">
                                    <input type="checkbox" name="permissoes[]" value="compras" x-model="selecoes" class="rounded text-blue-600 focus:ring-blue-500">
                                    <span class="text-slate-700">Módulo de Compras (Entradas)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        Salvar Perfil
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600">
                        <tr>
                            <th class="p-4 font-semibold">Perfil</th>
                            <th class="p-4 font-semibold">Módulos Permitidos</th>
                            <th class="p-4 font-semibold text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($perfis as $perfil)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-4 font-medium text-slate-800">{{ $perfil->descricao }}</td>
                                <td class="p-4 text-slate-500">
                                    <div class="flex flex-wrap gap-1">
                                        @if($perfil->permissoes && is_array($perfil->permissoes))
                                            @foreach($perfil->permissoes as $permissao)
                                                <span class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs">{{ ucfirst($permissao) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-xs text-slate-400 italic">Nenhuma permissão</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    @if($perfil->id !== 1 && $perfil->id !== auth()->user()->perfil_id)
                                        <a href="{{ route('perfis.edit', $perfil->id) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-white border border-slate-300 rounded shadow-sm text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            Editar
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Protegido</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection