@extends('layouts.app')

@section('titulo_pagina', 'Produtos')

@section('conteudo')
<body class="bg-slate-50 min-h-screen p-8 font-sans">

    <div class="max-w-7xl mx-auto">
        @if(session('sucesso'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative mb-6 flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-medium text-sm">{{ session('sucesso') }}</span>
            </div>
        @endif
        
        <div class="flex gap-3">
            <button type="button" onclick="abrirModalVeiculo()" class="flex items-center gap-2 bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg font-medium transition-colors shadow-sm text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Aplicações / Veículos
            </button>

            <button type="button" onclick="abrirModalCadastrar()" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Cadastrar Nova Peça
            </button>
        </div>

        <form method="GET" action="{{ route('produtos.index') }}" class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 flex flex-col lg:flex-row gap-4 items-end">
            
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-slate-700 mb-1">Buscar Peça</label>
                <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Nome, código interno ou código de barras..." 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
            </div>

            <div class="w-full lg:w-64 relative" 
                 x-data="{ aberto: false, busca: '{{ request('aplicacao') }}', todos: {{ json_encode($todosVeiculos->map(function($v) { return $v->marca.' '.$v->modelo.($v->ano ? ' ('.$v->ano.')' : ''); })) }} }" 
                 @click.away="aberto = false">
                <label class="block text-sm font-medium text-slate-700 mb-1">Aplicação Veicular (Tag)</label>
                <div class="relative">
                    <input type="text" name="aplicacao" x-model="busca" @focus="aberto = true" placeholder="Ex: Gol G5, Civic..." 
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                    <template x-if="busca">
                        <button type="button" @click="busca = ''" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </template>
                </div>
                
                <div x-show="aberto && busca !== ''" x-cloak class="absolute w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl z-50 max-h-48 overflow-y-auto">
                    <template x-for="item in todos.filter(t => t.toLowerCase().includes(busca.toLowerCase()))">
                        <div @click="busca = item; aberto = false" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm text-slate-700 border-b border-slate-100 last:border-0 transition-colors" x-text="item"></div>
                    </template>
                </div>
            </div>

            <div class="w-full lg:w-auto flex items-center h-10 mb-1">
                <label class="inline-flex items-center cursor-pointer select-none">
                    <input type="checkbox" name="estoque_baixo" value="1" {{ request('estoque_baixo') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                    <span class="ml-2 text-sm font-medium text-amber-700">Apenas estoque crítico</span>
                </label>
            </div>

            <div class="flex gap-2 w-full lg:w-auto">
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-medium px-6 py-2 rounded-lg transition-colors shadow-sm text-sm w-full lg:w-auto">
                    Filtrar
                </button>
                @if(request('busca') || request('aplicacao') || request('estoque_baixo'))
                    <a href="{{ route('produtos.index') }}" class="flex items-center justify-center text-slate-600 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-lg font-medium transition-colors border border-slate-200 text-sm">
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
                            <th scope="col" class="px-6 py-4">Cód. Interno / Barras</th>
                            <th scope="col" class="px-6 py-4">Peça / Descrição</th>
                            <th scope="col" class="px-6 py-4">Aplicação</th>
                            <th scope="col" class="px-6 py-4">Localização</th>
                            <th scope="col" class="px-6 py-4">Qtd. Estoque</th>
                            <th scope="col" class="px-6 py-4">Preço Venda</th>
                            <th scope="col" class="px-6 py-4 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
    @forelse($produtos as $produto)
        <tr class="hover:bg-slate-50 transition-colors {{ $produto->estoque <= $produto->quantidade_minima ? 'bg-amber-50/40 hover:bg-amber-50' : '' }}">
            <td class="px-6 py-4 font-mono text-xs text-slate-500">
                <span class="block font-bold text-slate-700">#{{ $produto->codigo_interno ?? 'N/A' }}</span>
                <span>{{ $produto->codigo_barras ?? 'Sem cód. barras' }}</span>
            </td>
            <td class="px-6 py-4">
                <div class="font-medium text-slate-800">{{ $produto->nome }}</div>
                <div class="text-xs text-slate-400">Marca: {{ $produto->marca ?? 'Não informada' }}</div>
            </td>
            <td class="px-6 py-4 text-xs">
                <div class="flex flex-wrap gap-1 max-w-[200px]">
                    @forelse($produto->veiculos as $v)
                        <span class="bg-blue-100 border border-blue-200 text-blue-800 px-2 py-0.5 rounded-full font-medium whitespace-nowrap">
                            {{ $v->marca }} {{ $v->modelo }} {{ $v->ano ? '('.$v->ano.')' : '' }}
                        </span>
                    @empty
                        <span class="bg-slate-100 text-slate-500 px-2 py-1 rounded">Universal/Geral</span>
                    @endforelse
                </div>
            </td>
            <td class="px-6 py-4 font-medium text-slate-700">{{ $produto->localizacao ?? 'Não definida' }}</td>
            <td class="px-6 py-4">
                @if($produto->estoque <= $produto->quantidade_minima)
                    <div class="text-red-600 font-bold flex items-center gap-1">
                        {{ $produto->estoque }} unidades
                        <span class="inline-block w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    </div>
                    <div class="text-xs text-amber-700 font-medium">Mínimo: {{ $produto->quantidade_minima }} (Comprar!)</div>
                @else
                    <div class="text-slate-800 font-semibold">{{ $produto->estoque }} unidades</div>
                    <div class="text-xs text-slate-400">Mínimo: {{ $produto->quantidade_minima }}</div>
                @endif
            </td>
            <td class="px-6 py-4 font-medium text-slate-900">R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
            
            <td class="px-6 py-4 text-right whitespace-nowrap min-w-[140px]">
                <div class="flex items-center justify-end gap-3">
                    <button type="button" 
                        onclick="abrirModalDescricao(this)" 
                        data-nome="{{ $produto->nome }}" 
                        data-descricao="{{ $produto->descricao ?? 'Nenhuma descrição cadastrada para esta peça.' }}"
                        class="text-blue-600 hover:text-blue-800 font-medium text-xs underline decoration-dotted transition-colors">
                        Descrição
                    </button>
                    <button type="button" 
                        onclick="abrirModalEditar(this)" 
                        data-id="{{ $produto->id }}"
                        data-nome="{{ $produto->nome }}"
                        data-marca="{{ $produto->marca }}"
                        data-descricao="{{ $produto->descricao }}"
                        data-preco="{{ $produto->preco }}"
                        data-estoque="{{ $produto->estoque }}"
                        data-qtd-min="{{ $produto->quantidade_minima }}"
                        data-cod-barras="{{ $produto->codigo_barras }}"
                        data-cod-interno="{{ $produto->codigo_interno }}"
                        data-localizacao="{{ $produto->localizacao }}"
                        data-veiculos-completos="{{ json_encode($produto->veiculos->map(function($v) { return ['id' => $v->id, 'nome' => $v->marca.' '.$v->modelo.($v->ano ? ' ('.$v->ano.')' : '')]; })) }}"
                        class="text-blue-600 hover:text-blue-800 font-medium text-xs transition-colors">
                        Editar
                    </button>
                </div>
            </td>
        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                Nenhum produto encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-between items-center text-xs text-slate-500">
                <div class="w-full">
                    {{ $produtos->appends(request()->query())->links() }}
                </div>
            </div>
        </div>

    </div>

    <div id="modal-descricao" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <h3 id="modal-titulo" class="text-lg font-bold text-slate-800">Descrição da Peça</h3>
                <button onclick="fecharModalDescricao()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="px-6 py-6 min-h-[100px]">
                <p id="modal-texto" class="text-slate-600 text-sm whitespace-pre-wrap leading-relaxed"></p>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 text-right">
                <button onclick="fecharModalDescricao()" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <div id="modal-cadastrar" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800">Cadastrar Nova Peça</h3>
                <button type="button" onclick="fecharModalCadastrar()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="form-cadastrar" method="POST" action="{{ route('produtos.store') }}">
                @csrf
                <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nome da Peça *</label>
                        <input type="text" name="nome" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Marca</label>
                        <input type="text" name="marca" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Localização</label>
                        <input type="text" name="localizacao" placeholder="Ex: Prateleira A" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Código de Barras</label>
                        <input type="text" name="codigo_barras" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Cód. Interno</label>
                        <input type="text" name="codigo_interno" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estoque Inicial *</label>
                        <input type="number" name="estoque" required value="0" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estoque Mínimo *</label>
                        <input type="number" name="quantidade_minima" required value="5" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Preço Venda (R$) *</label>
                        <input type="number" step="0.01" name="preco" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>

                    <div class="md:col-span-3 relative" 
                        x-data="seletorVeiculos({{ json_encode($todosVeiculos->map(function($v) { return ['id' => $v->id, 'nome' => $v->marca.' '.$v->modelo.($v->ano ? ' ('.$v->ano.')' : '')]; })) }})"
                        @limpar-tags.window="selecionadas = [];">
                        
                        <label class="block text-sm font-medium text-slate-700 mb-2">Aplicações / Tags Veiculares</label>

                        <div class="flex flex-wrap gap-2 mb-2 min-h-[32px] p-2 border border-slate-200 rounded-lg bg-slate-50/50">
                            <template x-for="tag in selecionadas" :key="tag.id">
                                <span class="inline-flex items-center gap-1 bg-blue-100 border border-blue-200 text-blue-800 px-2.5 py-1 rounded-md text-xs font-semibold shadow-sm">
                                    <span x-text="tag.nome"></span>
                                    <button type="button" @click="remover(tag.id)" class="text-blue-600 hover:text-red-600 focus:outline-none cursor-pointer">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    <input type="hidden" name="veiculos[]" :value="tag.id">
                                </span>
                            </template>
                            <span x-show="selecionadas.length === 0" class="text-xs text-slate-400 italic py-1">Nenhuma tag adicionada. Busque abaixo...</span>
                        </div>

                        <div class="relative">
                            <input type="text" 
                                x-ref="inputBusca"
                                x-model="busca" 
                                @focus="aberto = true" 
                                @click.away="aberto = false" 
                                placeholder="Digite para buscar marca ou modelo..." 
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">

                            <div x-show="aberto && filtrados.length > 0" x-cloak class="absolute w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl z-50 max-h-48 overflow-y-auto">
                                <template x-for="v in filtrados" :key="v.id">
                                    <div @click="adicionar(v)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm text-slate-700 border-b border-slate-100 last:border-0 transition-colors" x-text="v.nome"></div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Descrição</label>
                        <textarea name="descricao" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none"></textarea>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
                    <button type="button" onclick="fecharModalCadastrar()" class="text-slate-600 hover:text-slate-800 bg-white border border-slate-300 px-5 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
                        Salvar Produto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-editar" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800">Editar Peça</h3>
                <button onclick="fecharModalEditar()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="form-editar" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nome da Peça *</label>
                        <input type="text" id="edit-nome" name="nome" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Marca</label>
                        <input type="text" id="edit-marca" name="marca" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Localização</label>
                        <input type="text" id="edit-localizacao" name="localizacao" placeholder="Ex: Prateleira A" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Código de Barras</label>
                        <input type="text" id="edit-cod-barras" name="codigo_barras" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Cód. Interno</label>
                        <input type="text" id="edit-cod-interno" name="codigo_interno" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estoque Atual *</label>
                        <input type="number" id="edit-estoque" name="estoque" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estoque Mínimo *</label>
                        <input type="number" id="edit-qtd-min" name="quantidade_minima" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Preço Venda (R$) *</label>
                        <input type="number" step="0.01" id="edit-preco" name="preco" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>

                    <div class="md:col-span-3 relative" 
                        x-data="seletorVeiculos({{ json_encode($todosVeiculos->map(function($v) { return ['id' => $v->id, 'nome' => $v->marca.' '.$v->modelo.($v->ano ? ' ('.$v->ano.')' : '')]; })) }})"
                        @carregar-tags.window="selecionadas = $event.detail;">
                        
                        <label class="block text-sm font-medium text-slate-700 mb-2">Aplicações / Tags Veiculares</label>

                        <div class="flex flex-wrap gap-2 mb-2 min-h-[32px] p-2 border border-slate-200 rounded-lg bg-slate-50/50">
                            <template x-for="tag in selecionadas" :key="tag.id">
                                <span class="inline-flex items-center gap-1 bg-blue-100 border border-blue-200 text-blue-800 px-2.5 py-1 rounded-md text-xs font-semibold shadow-sm">
                                    <span x-text="tag.nome"></span>
                                    <button type="button" @click="remover(tag.id)" class="text-blue-600 hover:text-red-600 focus:outline-none cursor-pointer">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    <input type="hidden" name="veiculos[]" :value="tag.id">
                                </span>
                            </template>
                            <span x-show="selecionadas.length === 0" class="text-xs text-slate-400 italic py-1">Nenhuma tag adicionada. Busque abaixo...</span>
                        </div>

                        <div class="relative">
                            <input type="text" 
                                x-ref="inputBusca"
                                x-model="busca" 
                                @focus="aberto = true" 
                                @click.away="aberto = false" 
                                placeholder="Digite para buscar marca ou modelo..." 
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">

                            <div x-show="aberto && filtrados.length > 0" x-cloak class="absolute w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-xl z-50 max-h-48 overflow-y-auto">
                                <template x-for="v in filtrados" :key="v.id">
                                    <div @click="adicionar(v)" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm text-slate-700 border-b border-slate-100 last:border-0 transition-colors" x-text="v.nome"></div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Descrição</label>
                        <textarea id="edit-descricao" name="descricao" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none"></textarea>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
                    <button type="button" onclick="fecharModalEditar()" class="text-slate-600 hover:text-slate-800 bg-white border border-slate-300 px-5 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="modal-veiculo" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <h3 class="text-lg font-bold text-slate-800">Gerenciar Aplicações / Veículos</h3>
                <button type="button" onclick="fecharModalVeiculo()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 bg-white">
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Nova Tag Veicular</h4>
                    <form method="POST" action="{{ route('veiculos.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Montadora / Marca *</label>
                            <input type="text" name="marca" placeholder="Ex: Volkswagen" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Modelo *</label>
                            <input type="text" name="modelo" placeholder="Ex: Gol G5" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Ano Compatível (Opcional)</label>
                            <input type="number" name="ano" placeholder="Ex: 2012" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-lg font-medium text-sm transition-colors shadow-sm">
                                Criar Tag de Veículo
                            </button>
                        </div>
                    </form>
                </div>

                <div x-data="gerenciadorListaVeiculos({{ json_encode($todosVeiculos->map(function($v) { return ['marca' => $v->marca, 'modelo' => $v->modelo, 'ano' => $v->ano]; })) }})" class="flex flex-col border-t md:border-t-0 md:border-l border-slate-200 pt-6 md:pt-0 md:pl-6">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Aplicações Cadastradas</h4>
                    
                    <div class="relative mb-3">
                        <input type="text" x-model="busca" placeholder="Filtrar por marca, modelo ou ano..." class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-slate-50/50">
                        <span class="absolute left-3 top-2.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                    </div>

                    <div class="flex-1 max-h-64 overflow-y-auto border border-slate-200 rounded-lg divide-y divide-slate-100 bg-slate-50/30">
                        <template x-for="(v, index) in filtrados" :key="index">
                            <div class="px-3 py-2 flex justify-between items-center hover:bg-white transition-colors">
                                <div class="text-sm">
                                    <span class="font-bold text-slate-700" x-text="v.marca"></span>
                                    <span class="text-slate-600 ml-1" x-text="v.modelo"></span>
                                </div>
                                <span x-show="v.ano" class="bg-blue-50 border border-blue-100 text-blue-700 text-xs font-semibold px-2 py-0.5 rounded-md" x-text="v.ano"></span>
                            </div>
                        </template>
                        <div x-show="filtrados.length === 0" class="px-4 py-8 text-center text-xs text-slate-400 italic">
                            Nenhum veículo encontrado para o filtro.
                        </div>
                    </div>
                    
                    <div class="mt-2 text-right text-[11px] text-slate-400">
                        Total: <span class="font-bold text-slate-600" x-text="todos.length"></span> tags registradas
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-3 border-t border-slate-100 bg-slate-50 text-right">
                <button type="button" onclick="fecharModalVeiculo()" class="text-slate-600 hover:text-slate-800 bg-white border border-slate-300 px-4 py-1.5 rounded-lg font-medium text-xs transition-colors shadow-sm">
                    Fechar Janela
                </button>
            </div>
        </div>
    </div>

    <script>
        function abrirModalDescricao(elemento) {
            const nome = elemento.getAttribute('data-nome');
            const descricao = elemento.getAttribute('data-descricao');
            document.getElementById('modal-titulo').innerText = nome;
            document.getElementById('modal-texto').innerText = descricao;
            document.getElementById('modal-descricao').classList.remove('hidden');
        }

        function fecharModalDescricao() {
            document.getElementById('modal-descricao').classList.add('hidden');
        }

        function abrirModalVeiculo() { 
            document.getElementById('modal-veiculo').classList.remove('hidden'); 
        }
        
        function fecharModalVeiculo() { 
            document.getElementById('modal-veiculo').classList.add('hidden'); 
        }

        function abrirModalCadastrar() {
            window.dispatchEvent(new CustomEvent('limpar-tags'));
            document.getElementById('modal-cadastrar').classList.remove('hidden');
        }

        function fecharModalCadastrar() {
            document.getElementById('modal-cadastrar').classList.add('hidden');
        }

        function abrirModalEditar(elemento) {
            const id = elemento.getAttribute('data-id');
            const nome = elemento.getAttribute('data-nome');
            const marca = elemento.getAttribute('data-marca');
            const descricao = elemento.getAttribute('data-descricao');
            const preco = elemento.getAttribute('data-preco');
            const estoque = elemento.getAttribute('data-estoque');
            const qtdMin = elemento.getAttribute('data-qtd-min');
            const codBarras = elemento.getAttribute('data-cod-barras');
            const codInterno = elemento.getAttribute('data-cod-interno');
            const localizacao = elemento.getAttribute('data-localizacao');

            document.getElementById('edit-nome').value = nome;
            document.getElementById('edit-marca').value = marca;
            document.getElementById('edit-descricao').value = descricao;
            document.getElementById('edit-preco').value = preco;
            document.getElementById('edit-estoque').value = estoque;
            document.getElementById('edit-qtd-min').value = qtdMin;
            document.getElementById('edit-cod-barras').value = codBarras;
            document.getElementById('edit-cod-interno').value = codInterno;
            document.getElementById('edit-localizacao').value = localizacao;

            const form = document.getElementById('form-editar');
            form.action = `/produtos/${id}`;

            const veiculosCompletos = JSON.parse(elemento.getAttribute('data-veiculos-completos') || '[]');
            window.dispatchEvent(new CustomEvent('carregar-tags', { detail: veiculosCompletos }));

            document.getElementById('modal-editar').classList.remove('hidden');
        }

        function fecharModalEditar() {
            document.getElementById('modal-editar').classList.add('hidden');
        }

        window.addEventListener('mousedown', function(e) {
            const modalDesc = document.getElementById('modal-descricao');
            const modalEdit = document.getElementById('modal-editar');
            const modalCad = document.getElementById('modal-cadastrar');
            const modalVeic = document.getElementById('modal-veiculo');

            if (e.target === modalDesc) fecharModalDescricao();
            if (e.target === modalEdit) fecharModalEditar();
            if (e.target === modalCad) fecharModalCadastrar();
            if (e.target === modalVeic) fecharModalVeiculo();
        });
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('seletorVeiculos', (veiculosIniciais) => ({
                todos: veiculosIniciais,
                selecionadas: [],
                busca: '',
                aberto: false,

                get filtrados() {
                    let disponiveis = this.todos.filter(v => !this.selecionadas.some(s => s.id === v.id));
                    if (this.busca === '') return disponiveis;
                    const termo = this.busca.toLowerCase();
                    return disponiveis.filter(v => v.nome.toLowerCase().includes(termo));
                },
                adicionar(veiculo) {
                    this.selecionadas.push(veiculo);
                    this.busca = '';
                    this.$refs.inputBusca.focus();
                },
                remover(id) {
                    this.selecionadas = this.selecionadas.filter(v => v.id !== id);
                }
            }));

            Alpine.data('gerenciadorListaVeiculos', (veiculosIniciais) => ({
                todos: veiculosIniciais,
                busca: '',

                get filtrados() {
                    if (this.busca === '') return this.todos;
                    const termo = this.busca.toLowerCase();
                    return this.todos.filter(v => 
                        v.marca.toLowerCase().includes(termo) || 
                        v.modelo.toLowerCase().includes(termo) || 
                        (v.ano && v.ano.toString().includes(termo))
                    );
                }
            }));
        });
    </script>

</body>
</html>
@endsection