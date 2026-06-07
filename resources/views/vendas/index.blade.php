@extends('layouts.app')

@section('titulo_pagina', 'Vendas')

@section('conteudo')
<div class="max-w-7xl mx-auto">
    
    <div class="flex border-b border-slate-200 mb-6 bg-white p-2 rounded-xl shadow-sm gap-2">
        <button onclick="alternarAba('nova-venda')" id="btn-nova-venda" 
            class="tab-btn flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg font-medium text-sm transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nova Venda (PDV)
        </button>
        <button onclick="alternarAba('historico')" id="btn-historico" 
            class="tab-btn flex items-center gap-2 px-5 py-2.5 text-slate-600 hover:bg-slate-50 rounded-lg font-medium text-sm transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Histórico de Pedidos
        </button>
        <button onclick="alternarAba('devolucoes')" id="btn-devolucoes" 
            class="tab-btn flex items-center gap-2 px-5 py-2.5 text-slate-600 hover:bg-slate-50 rounded-lg font-medium text-sm transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>
            Devoluções / Trocas
        </button>
    </div>

    <div id="conteudo-nova-venda" class="tab-conteudo grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Adicionar Peças ao Pedido</h3>
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <input type="text" placeholder="Bipe o código de barras ou digite o código interno..." 
                            class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <button class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                        Filtrar por Carro
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-800">Itens Adicionados</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-100">
                                <th class="px-6 py-3">Código</th>
                                <th class="px-6 py-3">Produto / Aplicação</th>
                                <th class="px-6 py-3 text-center">Qtd</th>
                                <th class="px-6 py-3 text-right">Unitário</th>
                                <th class="px-6 py-3 text-right">Subtotal</th>
                                <th class="px-6 py-3 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            <tr>
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">INT-84920</td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-900">Pastilha de Freio Dianteira - Bosch</p>
                                    <p class="text-xs text-blue-600 font-medium mt-0.5">Compatível: Gol G5/G6, Fox 1.0 (2010-2015)</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="number" value="1" min="1" class="w-14 text-center px-1 py-1 border border-slate-300 rounded outline-none focus:ring-1 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 text-right">R$ 120,00</td>
                                <td class="px-6 py-4 text-right font-medium">R$ 120,00</td>
                                <td class="px-6 py-4 text-center">
                                    <button class="text-red-500 hover:text-red-700 p-1 rounded transition-colors cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <h3 class="text-base font-semibold text-slate-800 mb-6 border-b border-slate-100 pb-3">Resumo da Venda</h3>
                
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal dos Itens:</span>
                        <span class="font-medium text-slate-800">R$ 120,00</span>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Aplicar Desconto (R$)</label>
                        <input type="number" placeholder="0,00" class="w-full px-3 py-1.5 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-between text-base font-bold text-slate-900">
                        <span>Total Geral:</span>
                        <span class="text-xl text-blue-600">R$ 120,00</span>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Forma de Pagamento</label>
                    <select class="w-full px-3 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                        <option>Dinheiro</option>
                        <option>Pix</option>
                        <option>Cartão de Crédito</option>
                        <option>Cartão de Débito</option>
                    </select>
                </div>

                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-sm transition-colors mt-6 flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Finalizar e Baixar Estoque
                </button>
            </div>
        </div>
    </div>

    <div id="conteudo-historico" class="tab-conteudo hidden space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex flex-col md:flex-row gap-4 justify-between items-center">
            <h3 class="text-base font-semibold text-slate-800">Histórico de Pedidos Realizados</h3>
            <div class="flex gap-3">
                <input type="date" class="border border-slate-300 px-3 py-1.5 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 text-slate-600">
                <input type="text" placeholder="Buscar por número do pedido..." class="border border-slate-300 px-3 py-1.5 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500 text-slate-600 w-64">
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-semibold border-b border-slate-100">
                        <th class="px-6 py-3">Nº Pedido</th>
                        <th class="px-6 py-3">Data / Hora</th>
                        <th class="px-6 py-3">Vendedor</th>
                        <th class="px-6 py-3 text-right">Total</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    <tr>
                        <td class="px-6 py-4 font-semibold text-blue-600">#1024</td>
                        <td class="px-6 py-4 text-slate-500">07/06/2026 09:14</td>
                        <td class="px-6 py-4">Marcos Silva</td>
                        <td class="px-6 py-4 text-right font-medium">R$ 340,50</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 bg-green-50 text-green-700 font-medium text-xs rounded-full">Finalizado</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="conteudo-devolucoes" class="tab-conteudo hidden space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 max-w-xl mx-auto">
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Iniciar Processo de Devolução</h3>
                <p class="text-slate-500 text-sm mt-1">Busque o pedido original do cliente para fazer o estorno correto e retornar os produtos ao estoque.</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Número do Pedido Original</label>
                    <div class="flex gap-2">
                        <input type="text" placeholder="Ex: 1024" class="flex-1 px-4 py-2 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors cursor-pointer">
                            Buscar Venda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function alternarAba(abaId) {
        // 1. Esconde todos os conteúdos das abas
        document.querySelectorAll('.tab-conteudo').forEach(el => el.classList.add('hidden'));
        
        // 2. Mostra apenas o conteúdo da aba clicada
        document.getElementById('conteudo-' + abaId).classList.remove('hidden');
        
        // 3. Remove a estilização ativa (azul) de todos os botões
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('text-slate-600', 'hover:bg-slate-50');
        });
        
        // 4. Adiciona a estilização ativa no botão clicado
        const btnAtivo = document.getElementById('btn-' + abaId);
        btnAtivo.classList.remove('text-slate-600', 'hover:bg-slate-50');
        btnAtivo.classList.add('bg-blue-600', 'text-white');
    }
</script>
@endsection