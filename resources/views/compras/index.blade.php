@extends('layouts.app')

@section('titulo_pagina', 'Módulo de Compras / Entrada')

@section('conteudo')
<style>
    /* Remove setinhas dos inputs numéricos */
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    /* Configurações extras para garantir a impressão perfeita da folha A4 */
    @media print {
        @page { size: A4 portrait; margin: 10mm; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

<div x-data="moduloCompras()" class="w-full">

    <div class="flex border-b border-slate-200 mb-6 print:hidden">
        <button type="button" @click="aba = 'novo_pedido'" :class="aba === 'novo_pedido' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="py-4 px-6 border-b-2 font-medium text-sm transition-all cursor-pointer">
            Nova Compra
        </button>
        <button type="button" @click="aba = 'listagem'" :class="aba === 'listagem' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="py-4 px-6 border-b-2 font-medium text-sm transition-all cursor-pointer">
            Histórico
        </button>
    </div>

    <div x-show="aba === 'novo_pedido'" class="space-y-4">

        <div class="flex justify-end gap-3 print:hidden">
            <button type="button" onclick="window.print()" class="bg-slate-600 hover:bg-slate-700 text-white font-medium py-2 px-4 rounded flex items-center gap-2 transition-colors cursor-pointer">
                Imprimir Recibo
            </button>
            <button type="button" @click="finalizarCompra()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded flex items-center gap-2 transition-colors cursor-pointer shadow-sm">
                Finalizar Compra
            </button>
        </div>

        <div class="w-[210mm] min-h-[297mm] max-w-full mx-auto bg-white p-6 border-2 border-gray-800 text-black font-sans shadow-lg text-sm flex flex-col mb-10 print:border-none print:shadow-none print:m-0 print:p-0 print:w-full print:min-h-0">

            <div class="flex justify-between items-stretch border-b-2 border-gray-800 pb-4 mb-2 gap-4">
                <div class="w-1/4 border-2 border-gray-800 flex items-center justify-center p-2 bg-gray-50">
                    <span class="text-xs text-gray-500 text-center">[Logo]</span>
                </div>
                <div class="w-2/4 border-2 border-gray-800 p-2 text-center flex flex-col justify-center">
                    <h1 class="font-bold text-lg uppercase">ORDEM DE COMPRA</h1>
                    <p class="text-[10px] leading-tight">Sua Empresa LTDA</p>
                    <p class="text-[10px] leading-tight">CNPJ: 00.000.000/0000-00</p>
                </div>
                <div class="w-1/4 border-2 border-gray-800 p-2 flex flex-col items-center justify-center bg-gray-50">
                    <span class="text-xs font-bold uppercase">Nº da Compra</span>
                    <span class="text-lg font-bold text-red-600">PENDENTE</span>
                </div>
            </div>

            <div class="border-2 border-gray-800 mb-2">
                <div class="bg-gray-200 text-center font-bold border-b-2 border-gray-800 uppercase tracking-widest text-xs py-1">Dados do Fornecedor</div>

                <div class="flex border-b border-gray-800 relative" @click.away="mostrarDropdownFornecedor = false">
                    <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">RAZÃO SOCIAL</div>
                    <input type="text" x-model="buscaFornecedor" @input="mostrarDropdownFornecedor = true; fornecedor.cnpj = ''; fornecedor.telefone = ''; fornecedor.email = '';" @focus="mostrarDropdownFornecedor = true" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50" autocomplete="off" placeholder="Digite para buscar...">

                    <div x-show="mostrarDropdownFornecedor && fornecedoresFiltrados.length > 0" x-transition class="absolute top-full left-24 right-0 bg-white border border-gray-300 shadow-xl z-50 max-h-40 overflow-y-auto print:hidden">
                        <template x-for="f in fornecedoresFiltrados" :key="f.cnpj">
                            <div @click="selecionarFornecedor(f)" class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 text-xs">
                                <span class="font-bold block" x-text="f.nome_exibicao"></span>
                                <span class="text-gray-500 text-[10px]" x-text="'CNPJ: ' + (formatarCNPJ(f.cnpj) || 'N/A') + ' | Telefone: ' + (formatarTelefone(f.telefone) || 'N/A')"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex border-gray-800">
                    <div class="w-14     shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CNPJ</div>
                    <input type="text" x-model="fornecedor.cnpj" @input="fornecedor.cnpj = formatarCNPJ(fornecedor.cnpj)" class="w-[20%] min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50 border-r border-gray-800">
                    
                    <div class="w-20 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">TELEFONE</div>
                    <input type="text" x-model="fornecedor.telefone" @input="fornecedor.telefone = formatarTelefone(fornecedor.telefone)" class="w-[20%] min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50 border-r border-gray-800">
                    
                    <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">E-MAIL</div>
                    <input type="email" x-model="fornecedor.email" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50">
                </div>
            </div>

            <div class="border-2 border-gray-800 mb-2 flex-1 flex flex-col">
                <div class="bg-gray-200 text-center font-bold border-b-2 border-gray-800 uppercase tracking-widest text-xs py-1">Itens Adquiridos</div>

                <div class="flex-1">
                    <table class="w-full text-xs text-left border-collapse relative">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-800">
                                <th class="border-r border-gray-800 p-1 w-12 text-center">ITEM</th>
                                <th class="border-r border-gray-800 p-1 pl-2">PRODUTO/PEÇA</th>
                                <th class="border-r border-gray-800 p-1 w-20 text-center">QUANT.</th>
                                <th class="p-1 w-32 text-center">CUSTO Unit.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in itens" :key="item.id">
                                <tr class="border-b border-gray-400" @click.away="item.dropdown = false">
                                    <td class="border-r border-gray-800 text-center text-gray-500 bg-gray-50" x-text="index + 1"></td>
                                    <td class="border-r border-gray-800 p-0 relative">
                                        <input type="text" x-model="item.nome" @input="item.dropdown = true" @focus="item.dropdown = true" class="w-full h-full px-2 py-1 outline-none uppercase focus:bg-yellow-50" autocomplete="off" placeholder="Nome do produto...">

                                        <div x-show="item.dropdown && produtosFiltrados(item.nome).length > 0" class="absolute top-full left-0 right-0 bg-white border border-gray-300 shadow-xl z-50 max-h-40 overflow-y-auto print:hidden">
                                            <template x-for="prod in produtosFiltrados(item.nome)">
                                                <div @click="selecionarProduto(index, prod)" class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 text-xs flex justify-between">
                                                    <span class="font-bold" x-text="prod.nome"></span>
                                                    <span class="text-blue-600 font-medium">Custo: R$ <span x-text="formatarMoeda(prod.valor)"></span></span>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="border-r border-gray-800 p-0">
                                        <input type="number" min="1" x-model="item.qtd" class="w-full h-full px-1 py-1 text-center outline-none focus:bg-yellow-50">
                                    </td>
                                    <td class="p-0">
                                        <input type="number" step="0.01" min="0" x-model="item.valor" class="w-full h-full px-2 py-1 text-right outline-none focus:bg-yellow-50">
                                    </td>
                                </tr>
                            </template>

                            <tr class="print:hidden">
                                <td colspan="4" class="p-1">
                                    <button type="button" @click="adicionarItem()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2 rounded text-xs border border-dashed border-slate-300 transition-colors cursor-pointer">
                                        + Adicionar Linha de Produto
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex border-t-2 border-gray-800 bg-gray-50 text-xs mt-auto">
                    <div class="w-1/2 flex border-r border-gray-800 items-center px-2 py-1 gap-1">
                        <span class="font-bold shrink-0">DESCONTO NEGOCIADO:</span>
                        <select x-model="tipoDesconto" class="bg-transparent border border-gray-400 rounded px-1 outline-none font-bold text-xs py-0.5 cursor-pointer print:border-none print:appearance-none">
                            <option value="reais">R$</option>
                            <option value="porcentagem">%</option>
                        </select>
                        <input type="number" step="0.01" min="0" x-model="valorDesconto" class="flex-1 min-w-0 p-1 outline-none bg-transparent focus:bg-yellow-100 text-right pr-2">
                    </div>
                    <div class="w-1/2 flex items-center bg-gray-200 px-2 py-1">
                        <span class="font-bold shrink-0 text-slate-900">TOTAL: R$</span>
                        <input type="text" readonly :value="formatarMoeda(totalFinal)" class="flex-1 min-w-0 p-1 outline-none bg-transparent text-right pr-2 font-bold text-xl text-blue-700 cursor-not-allowed print:text-black">
                    </div>
                </div>
            </div>

        </div> 
    </div>
    <div x-show="aba === 'listagem'" style="display: none;">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Histórico de Compras</h2>
        <div class="mb-6 flex flex-col md:flex-row gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
            <div class="flex-1">
                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Buscar por Fornecedor</label>
                <input type="text" x-model="filtroFornecedor" class="w-full px-3 py-2 border border-slate-300 rounded outline-none text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 uppercase" placeholder="Ex: Nome da empresa...">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Buscar por Peça/Produto</label>
                <input type="text" x-model="filtroProduto" class="w-full px-3 py-2 border border-slate-300 rounded outline-none text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 uppercase" placeholder="Ex: Parafuso, correia...">
            </div>
            <div class="flex items-end">
                <button type="button" @click="filtroFornecedor = ''; filtroProduto = ''" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-600 font-bold rounded text-sm transition-colors cursor-pointer border border-slate-300">
                    Limpar
                </button>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 text-sm uppercase">
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">Data</th>
                        <th class="p-4 font-semibold">Fornecedor</th>
                        <th class="p-4 font-semibold text-right">Total</th>
                        <th class="p-4 font-semibold text-center print:hidden">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="compra in comprasFiltradas" :key="compra.id">
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            
                            <td class="p-4 text-slate-800" x-text="'#' + compra.id"></td>
                            
                            <td class="p-4 text-slate-600" x-text="new Date(compra.data_compra).toLocaleDateString('pt-BR')"></td>
                            
                            <td class="p-4 font-medium text-slate-800" x-text="compra.fornecedor_nome"></td>
                            
                            <td class="p-4 text-right font-bold text-blue-600" x-text="'R$ ' + formatarMoeda(compra.total)"></td>

                            
                            <td class="p-4 text-center print:hidden">
                                <button type="button" @click="abrirDetalhes(compra)" class="bg-blue-50 text-blue-600 px-3 py-1 rounded text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-colors cursor-pointer shadow-sm">
                                    Ver Recibo
                                </button>
                            </td>
                            
                        </tr>
                    </template>
                    
                    <tr x-show="comprasFiltradas.length === 0">
                        <td colspan="4" class="p-8 text-center text-slate-500">
                            Nenhuma compra registrada ainda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div x-show="mostrarModalDetalhes" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 print:p-0 print:block print:bg-transparent print:absolute print:inset-0">
        
        <div class="absolute inset-0 print:hidden" @click="fecharDetalhes()"></div>

        <div class="relative w-full max-w-4xl max-h-screen overflow-y-auto bg-gray-100 p-6 rounded-xl shadow-2xl print:max-h-none print:bg-white print:shadow-none print:rounded-none print:p-0">
            
            <div class="flex justify-between items-center mb-4 print:hidden sticky top-0 bg-gray-100 py-2 z-10 border-b border-gray-300">
                <h3 class="font-bold text-lg text-slate-800">Detalhes da Compra #<span x-text="compraSelecionada?.id"></span></h3>
                <div class="flex gap-2">
                    <button type="button" onclick="window.print()" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded text-sm font-bold flex items-center gap-2 cursor-pointer">
                        🖨️ Imprimir
                    </button>
                    <button type="button" @click="fecharDetalhes()" class="bg-red-100 text-red-600 hover:bg-red-600 hover:text-white px-4 py-2 rounded text-sm font-bold transition-colors cursor-pointer">
                        ✕ Fechar
                    </button>
                </div>
            </div>

            <template x-if="compraSelecionada">
                <div class="w-[210mm] min-h-[297mm] max-w-full mx-auto bg-white p-6 border-2 border-gray-800 text-black font-sans text-sm flex flex-col print:border-none print:w-full print:min-h-0 print:m-0 print:p-0">
                    
                    <div class="flex justify-between items-stretch border-b-2 border-gray-800 pb-4 mb-2 gap-4">
                        <div class="w-1/4 border-2 border-gray-800 flex items-center justify-center p-2 bg-gray-50">
                            <span class="text-xs text-gray-500 text-center">[Logo]</span>
                        </div>
                        <div class="w-2/4 border-2 border-gray-800 p-2 text-center flex flex-col justify-center">
                            <h1 class="font-bold text-lg uppercase">ORDEM DE COMPRA</h1>
                            <p class="text-[10px] leading-tight">Sua Empresa LTDA</p>
                            <p class="text-[10px] leading-tight">CNPJ: 00.000.000/0000-00</p>
                        </div>
                        <div class="w-1/4 border-2 border-gray-800 p-2 flex flex-col items-center justify-center bg-gray-50">
                            <span class="text-xs font-bold uppercase">Nº da Compra</span>
                            <span class="text-lg font-bold text-gray-800" x-text="compraSelecionada.id"></span>
                            <span class="text-[10px] text-gray-600" x-text="new Date(compraSelecionada.data_compra).toLocaleDateString('pt-BR')"></span>
                        </div>
                    </div>

                    <div class="border-2 border-gray-800 mb-2">
                        <div class="bg-gray-200 text-center font-bold border-b-2 border-gray-800 uppercase tracking-widest text-xs py-1">Dados do Fornecedor</div>

                        <div class="flex border-b border-gray-800 relative">
                            <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">RAZÃO SOCIAL</div>
                            <div class="flex-1 px-2 py-1 text-xs uppercase font-medium" x-text="compraSelecionada.fornecedor?.razao_social || compraSelecionada.fornecedor?.nome_fantasia || 'Não informado'"></div>
                        </div>

                        <div class="flex border-gray-800">
                            <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CNPJ</div>
                            <div class="w-[28%] px-2 py-1 text-xs border-r border-gray-800" x-text="formatarCNPJ(compraSelecionada.fornecedor?.cnpj)"></div>
                            
                            <div class="w-20 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">TELEFONE</div>
                            <div class="w-[25%] px-2 py-1 text-xs uppercase border-r border-gray-800" x-text="formatarTelefone(compraSelecionada.fornecedor?.telefone)"></div>
                            
                            <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">E-MAIL</div>
                            <div class="flex-1 px-2 py-1 text-xs" x-text="compraSelecionada.fornecedor?.email || 'N/A'"></div>
                        </div>
                    </div>

                    <div class="border-2 border-gray-800 mb-2 flex-1 flex flex-col">
                        <div class="bg-gray-200 text-center font-bold border-b-2 border-gray-800 uppercase tracking-widest text-xs py-1">Itens Adquiridos</div>

                        <div class="flex-1">
                            <table class="w-full text-xs text-left border-collapse relative">
                                <thead>
                                    <tr class="bg-gray-50 border-b-2 border-gray-800">
                                        <th class="border-r border-gray-800 p-1 w-12 text-center">ITEM</th>
                                        <th class="border-r border-gray-800 p-1 pl-2">PRODUTO/PEÇA</th>
                                        <th class="border-r border-gray-800 p-1 w-20 text-center">QUANT.</th>
                                        <th class="p-1 w-32 text-center">CUSTO Unit.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in compraSelecionada.itens" :key="index">
                                        <tr class="border-b border-gray-400">
                                            <td class="border-r border-gray-800 text-center text-gray-500 bg-gray-50 py-1" x-text="index + 1"></td>
                                            <td class="border-r border-gray-800 px-2 py-1 uppercase font-medium" x-text="item.nome || item.produto?.nome || 'Item avulso'"></td>
                                            <td class="border-r border-gray-800 px-2 py-1 text-center" x-text="item.qtd || item.quantidade"></td>
                                            <td class="px-2 py-1 text-right" x-text="'R$ ' + formatarMoeda(item.valor || item.preco)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex border-t-2 border-gray-800 bg-gray-50 text-xs mt-auto">
                            <div class="w-1/2 flex border-r border-gray-800 items-center px-2 py-1 gap-1"></div>
                            
                            <div class="w-1/2 flex items-center bg-gray-200 px-2 py-1">
                                <span class="font-bold shrink-0 text-slate-900">TOTAL PAGO: R$</span>
                                <div class="flex-1 text-right pr-2 font-bold text-xl text-black" x-text="formatarMoeda(compraSelecionada.total)"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </template>
        </div>
    </div>
    </div>
<script>
    // Usamos este evento para registrar o componente antes do Alpine iniciar na tela
    document.addEventListener('alpine:init', () => {
        Alpine.data('moduloCompras', () => ({
            aba: 'novo_pedido',
            comprasSalvas: [], 

            filtroFornecedor: '',
            filtroProduto: '',

            get comprasFiltradas() {
                let lista = this.comprasSalvas;

                // 1. Filtra por Fornecedor (se houver texto)
                if (this.filtroFornecedor.trim() !== '') {
                    let buscaForn = this.normalizarTexto(this.filtroFornecedor);
                    lista = lista.filter(compra => {
                        let nomeFornecedor = this.normalizarTexto(compra.fornecedor?.razao_social || compra.fornecedor?.nome_fantasia || compra.fornecedor_nome || '');
                        return nomeFornecedor.includes(buscaForn);
                    });
                }

                // 2. Filtra por Produto/Peça dentro da compra (se houver texto)
                if (this.filtroProduto.trim() !== '') {
                    let buscaProd = this.normalizarTexto(this.filtroProduto);
                    lista = lista.filter(compra => {
                        // Se a compra não tem itens cadastrados, já pula fora
                        if (!compra.itens || compra.itens.length === 0) return false;
                        
                        // Verifica se algum (.some) item desta compra bate com a pesquisa
                        return compra.itens.some(item => {
                            let nomeItem = this.normalizarTexto(item.produto?.nome || item.nome || '');
                            return nomeItem.includes(buscaProd);
                        });
                    });
                }

                return lista;
            },
            
            // --- LÓGICA DO MODAL DE DETALHES ---
            mostrarModalDetalhes: false,
            compraSelecionada: null,

            abrirDetalhes(compra) {
                this.compraSelecionada = compra;
                this.mostrarModalDetalhes = true;
            },
            
            fecharDetalhes() {
                this.mostrarModalDetalhes = false;
                this.compraSelecionada = null;
            },
            
            normalizarTexto(texto) {
                if (!texto) return '';
                return texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
            },
            formatarMoeda(valor) {
                let numero = Number(valor) || 0;
                return numero.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
            formatarCNPJ(cnpj) {
                if (!cnpj) return '';
                // Remove tudo que não for número
                let v = cnpj.replace(/\D/g, '');
                
                // Aplica a máscara progressivamente
                v = v.replace(/^(\d{2})(\d)/, '$1.$2');
                v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
                v = v.replace(/(\d{4})(\d)/, '$1-$2');
                
                // Trava no limite de 18 caracteres (00.000.000/0000-00)
                return v.substring(0, 18);
            },

            formatarTelefone(tel) {
                if (!tel) return '';
                // Remove tudo que não for número
                let v = tel.replace(/\D/g, '');
                
                // Adiciona o parênteses do DDD
                v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
                
                // Define se é telefone fixo (10 dígitos) ou celular (11 dígitos)
                if (v.length <= 13) { 
                    v = v.replace(/(\d{4})(\d)/, '$1-$2'); // Fixo: (XX) XXXX-XXXX
                } else {
                    v = v.replace(/(\d{5})(\d)/, '$1-$2'); // Celular: (XX) XXXXX-XXXX
                }
                
                // Trava no limite de 15 caracteres
                return v.substring(0, 15);
            },
            // --- LÓGICA DE FORNECEDORES ---
            buscaFornecedor: '',
            mostrarDropdownFornecedor: false,
            fornecedor: { id: null, nome_fantasia: '', razao_social: '', cnpj: '', telefone: '', email: '' },
            
            // Injeção segura do Laravel dentro do <script> (sem risco de quebrar o HTML)
            fornecedoresDB: @json($fornecedores).map(f => ({
                ...f,
                nome_exibicao: f.nome_fantasia || f.razao_social || 'SEM NOME DEFINIDO'
            })),
            
            get fornecedoresFiltrados() {
                if(this.buscaFornecedor === '') return [];
                let busca = this.normalizarTexto(this.buscaFornecedor);
                return this.fornecedoresDB.filter(f => this.normalizarTexto(f.nome_exibicao).includes(busca));
            },
            
           selecionarFornecedor(f) {
                this.buscaFornecedor = f.nome_exibicao;
                
                this.fornecedor = { 
                    ...f,
                    cnpj: this.formatarCNPJ(f.cnpj),
                    telefone: this.formatarTelefone(f.telefone)
                };
                
                this.mostrarDropdownFornecedor = false;
            },

            // --- LÓGICA DE PRODUTOS E ITENS ---
            itens: [
                { id: Date.now(), produto_id: null, nome: '', qtd: '1', valor: '', dropdown: false }
            ],
            
            adicionarItem() {
                this.itens.push({ id: Date.now(), produto_id: null, nome: '', qtd: '1', valor: '', dropdown: false });
            },
            
            produtosDB: @json($produtos),
            
            produtosFiltrados(busca) {
                if(busca === '') return [];
                let buscaNorm = this.normalizarTexto(busca);
                return this.produtosDB.filter(p => this.normalizarTexto(p.nome).includes(buscaNorm));
            },
            
            selecionarProduto(index, prod) {
                this.itens[index].produto_id = prod.id; 
                this.itens[index].nome = prod.nome;
                this.itens[index].valor = prod.preco; 
                this.itens[index].dropdown = false;
            },

            // --- LÓGICA DE MATEMÁTICA ---
            tipoDesconto: 'reais',
            valorDesconto: '',
            get subtotal() {
                return this.itens.reduce((soma, item) => soma + ((Number(item.qtd) || 0) * (Number(item.valor) || 0)), 0);
            },
            get totalFinal() {
                let sub = this.subtotal;
                let desc = Number(this.valorDesconto) || 0;
                return (this.tipoDesconto === 'porcentagem') ? Math.max(0, sub - (sub * (desc / 100))) : Math.max(0, sub - desc);
            },

            // --- INTEGRAÇÃO API ---
            async finalizarCompra() {
                if(!this.fornecedor.id) return alert('Selecione um fornecedor da lista!');
                if(!this.itens[0].nome) return alert('Adicione pelo menos um produto!');

                let payload = {
                    fornecedor_id: this.fornecedor.id,
                    total: this.totalFinal,
                    itens: this.itens
                };

                try {
                    let response = await fetch('/compras', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content
                        },
                        body: JSON.stringify(payload)
                    });

                    let data = await response.json();

                    if(response.ok && data.success) {
                        if(data.redirecionar_para) {
                            alert('Redirecionando para complementar cadastro da nova peça...');
                            window.location.href = data.redirecionar_para;
                        } else {
                            alert('Compra salva com sucesso!');
                            this.carregarCompras();
                            this.aba = 'listagem';
                            this.itens = [{ id: Date.now(), produto_id: null, nome: '', qtd: '1', valor: '', dropdown: false }];
                            this.valorDesconto = '';
                            this.buscaFornecedor = '';
                            this.fornecedor = { id: null };
                        }
                    } else {
                        alert('Erro ao salvar: ' + (data.error || 'Erro desconhecido no servidor.'));
                    }
                } catch(e) {
                    console.error(e);
                    alert('Falha ao comunicar com o servidor.');
                }
            },

            async carregarCompras() {
                try {
                    let response = await fetch('/api/compras/listar');
                    if(response.ok) {
                        this.comprasSalvas = await response.json();
                    }
                } catch(e) {
                    console.error('Erro ao carregar', e);
                }
            },

            init() {
                this.carregarCompras(); 
            }
        }));
    });
    
</script>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection