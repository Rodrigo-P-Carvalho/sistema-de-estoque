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
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Catálogo & Estoque de Peças</h2>
                <p class="text-slate-500 text-sm mt-1">Controle de inventário, localizações e aplicações veiculares.</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="abrirModalCadastrar()" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Cadastrar Nova Peça
                </button>
            </div>
        </div>

        <form method="GET" action="{{ route('produtos.index') }}" class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 flex flex-col lg:flex-row gap-4 items-end">
            
            <div class="flex-1 w-full">
                <label class="block text-sm font-medium text-slate-700 mb-1">Buscar Peça</label>
                <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Nome, código interno ou código de barras..." 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
            </div>

            <div class="w-full lg:w-64">
                <label class="block text-sm font-medium text-slate-700 mb-1">Aplicação Veicular</label>
                <input type="text" name="aplicacao" value="{{ request('aplicacao') }}" placeholder="Ex: Gol G5, Civic 2014..." 
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
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
                {{-- Se criar a coluna aplicacao_veicular no futuro, mostre-a aqui --}}
                <span class="bg-slate-200 text-slate-700 px-2 py-1 rounded">Geral</span>
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
            <td class="px-6 py-4 text-right space-x-3">
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
                class="text-blue-600 hover:text-blue-800 font-medium text-xs transition-colors">
                Editar
            </button>
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
                @method('PUT') <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    
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

    <script>
        function abrirModalDescricao(elemento) {
            // Pega os dados do botão clicado
            const nome = elemento.getAttribute('data-nome');
            const descricao = elemento.getAttribute('data-descricao');
            
            // Injeta os dados no Modal
            document.getElementById('modal-titulo').innerText = nome;
            document.getElementById('modal-texto').innerText = descricao;
            
            // Mostra o Modal
            document.getElementById('modal-descricao').classList.remove('hidden');
        }

        function fecharModalDescricao() {
            // Esconde o Modal
            document.getElementById('modal-descricao').classList.add('hidden');
        }

        // Fecha o modal se o usuário clicar fora da caixa branca
        document.getElementById('modal-descricao').addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModalDescricao();
            }
        });
        function abrirModalCadastrar() {
            document.getElementById('modal-cadastrar').classList.remove('hidden');
        }

        function fecharModalCadastrar() {
            document.getElementById('modal-cadastrar').classList.add('hidden');
        }
        function abrirModalEditar(elemento) {
            // 1. Pega os dados do botão
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

            // 2. Preenche os inputs do formulário
            document.getElementById('edit-nome').value = nome;
            document.getElementById('edit-marca').value = marca;
            document.getElementById('edit-descricao').value = descricao;
            document.getElementById('edit-preco').value = preco;
            document.getElementById('edit-estoque').value = estoque;
            document.getElementById('edit-qtd-min').value = qtdMin;
            document.getElementById('edit-cod-barras').value = codBarras;
            document.getElementById('edit-cod-interno').value = codInterno;
            document.getElementById('edit-localizacao').value = localizacao;

            // 3. Atualiza a URL de destino do formulário para salvar no ID correto
            const form = document.getElementById('form-editar');
            form.action = `/produtos/${id}`; // Garanta que sua rota seja assim!

            // 4. Mostra o Modal
            document.getElementById('modal-editar').classList.remove('hidden');
        }

        function fecharModalEditar() {
            document.getElementById('modal-editar').classList.add('hidden');
        }

        // Para fechar clicando fora (opcional, já integrado com o seu código anterior)
        window.addEventListener('click', function(e) {
            const modalDesc = document.getElementById('modal-descricao');
            const modalEdit = document.getElementById('modal-editar');
            const modalCad = document.getElementById('modal-cadastrar');
            if (e.target === modalDesc) fecharModalDescricao();
            if (e.target === modalEdit) fecharModalEditar();
            if (e.target === modalCad) fecharModalCadastrar();
        });
    </script>

</body>
</html>
@endsection