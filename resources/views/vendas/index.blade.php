@extends('layouts.app')

@section('titulo_pagina', 'Módulo de Pedidos')

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

<div x-data="moduloVendas()" class="w-full">

    <div class="flex border-b border-slate-200 mb-6 print:hidden">
        <button @click="aba = 'novo_pedido'" :class="aba === 'novo_pedido' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="py-4 px-6 border-b-2 font-medium text-sm transition-all cursor-pointer">
            Novo Pedido
        </button>
        <button @click="aba = 'listagem'" :class="aba === 'listagem' ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="py-4 px-6 border-b-2 font-medium text-sm transition-all cursor-pointer">
            Listagem Pedidos
        </button>
    </div>

    <div x-show="aba === 'novo_pedido'" class="space-y-4">

        <div class="flex justify-end gap-3 print:hidden">
            <button onclick="window.print()" class="bg-slate-600 hover:bg-slate-700 text-white font-medium py-2 px-4 rounded flex items-center gap-2 transition-colors cursor-pointer">
                Imprimir Recibo
            </button>
            <button @click="finalizarVenda()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded flex items-center gap-2 transition-colors cursor-pointer shadow-sm">
                Finalizar Pedido
            </button>
        </div>

        <div class="w-[210mm] min-h-[297mm] max-w-full mx-auto bg-white p-6 border-2 border-gray-800 text-black font-sans shadow-lg text-sm flex flex-col mb-10 print:border-none print:shadow-none print:m-0 print:p-0 print:w-full print:min-h-0">

            <div class="flex justify-between items-stretch border-b-2 border-gray-800 pb-4 mb-2 gap-4">
                <div class="w-1/4 border-2 border-gray-800 flex items-center justify-center p-2 bg-gray-50">
                    <span class="text-xs text-gray-500 text-center">[Logo]</span>
                </div>
                <div class="w-2/4 border-2 border-gray-800 p-2 text-center flex flex-col justify-center">
                    <h1 class="font-bold text-lg uppercase">MINHA PÁGINA INICIAL SERVIÇOS</h1>
                    <p class="text-[10px] leading-tight">CPF/CNPJ: 00.000.000/0000-00 - IE: 111111111</p>
                    <p class="text-[10px] leading-tight">Rua Barão de Jaguara, nº 1000</p>
                    <p class="text-[10px] leading-tight">Centro - Campinas - SP - Cep: 13015-001</p>
                    <p class="text-[10px] leading-tight">Tel: (19) 3333-3333 / E-mail: contato@empresa.com.br</p>
                </div>
                <div class="w-1/4 border-2 border-gray-800 p-2 flex flex-col items-center justify-center bg-gray-50">
                    <span class="text-xs font-bold uppercase">Nº do Pedido</span>
                    <span class="text-lg font-bold text-red-600">PENDENTE</span>
                </div>
            </div>

            <div class="border-2 border-gray-800 mb-2">
                <div class="bg-gray-200 text-center font-bold border-b-2 border-gray-800 uppercase tracking-widest text-xs py-1">Cliente</div>

                <div class="flex border-b border-gray-800 relative" @click.away="mostrarDropdownCliente = false">
                    <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">NOME</div>
                    <input type="text" x-model="buscaCliente" @input="mostrarDropdownCliente = true; cliente.nome = buscaCliente" @focus="mostrarDropdownCliente = true" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50" autocomplete="off" placeholder="">
                    <div x-show="mostrarDropdownCliente && clientesFiltrados.length > 0" class="absolute top-full left-24 right-0 bg-white border border-gray-300 shadow-xl z-50 max-h-40 overflow-y-auto print:hidden">
                        <template x-for="c in clientesFiltrados">
                            <div @click="selecionarCliente(c)" class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 text-xs">
                                <span class="font-bold" x-text="c.nome"></span>
                            </div>
                        </template>
                    </div>  
                </div>

                <div class="flex border-b border-gray-800">
                    <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">TELEFONE</div>
                    <input type="text" x-model="cliente.telefone" @input="cliente.telefone = formatarTelefone(cliente.telefone)" class="w-1/3 min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50 border-r border-gray-800">
                <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">EMAIL</div>
                    <input type="email" x-model="cliente.email" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50">
                </div>

                <div class="flex border-b border-gray-800">
                    <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CPF/CNPJ</div>
                    <input type="text" x-model="cliente.cpf_cnpj" @input="cliente.cpf_cnpj = formatarCPFCNPJ(cliente.cpf_cnpj)" class="w-1/2 min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50 border-r border-gray-800">
                    <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">RG/IE</div>
                    <input type="text" x-model="cliente.rg_ie" @input="cliente.rg_ie = cliente.rg_ie.replace(/[^0-9xX]/g, '')" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50">
                </div>

                <div class="flex border-b border-gray-800">
                    <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">ENDEREÇO</div>
                    <input type="text" x-model="cliente.endereco" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50">
                </div>

                <div class="flex">
                    <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">BAIRRO</div>
                    <input type="text" x-model="cliente.bairro" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50 border-r border-gray-800">
                    <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CIDADE</div>
                    <input type="text" x-model="cliente.cidade" class="w-32 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50 border-r border-gray-800">
                    <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">ESTADO</div>
                    <input type="text" x-model="cliente.estado" class="w-12 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50 border-r border-gray-800" maxlength="2">
                    <div class="w-12 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CEP</div>
                    <input type="text" x-model="cliente.cep" @input="cliente.cep = formatarCEP(cliente.cep)" class="w-24 min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50">
                </div>
            </div>

            <div class="border-2 border-gray-800 mb-2 flex-1 flex flex-col">
                <div class="bg-gray-200 text-center font-bold border-b-2 border-gray-800 uppercase tracking-widest text-xs py-1">Orçamento</div>

                <div class="flex-1">
                    <table class="w-full text-xs text-left border-collapse relative">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-800">
                                <th x-show="itens.length > 1" class="border-r border-gray-800 p-1 w-8 text-center print:hidden"></th>
                                <th class="border-r border-gray-800 p-1 w-12 text-center">ITEM</th>
                                <th class="border-r border-gray-800 p-1 pl-2">PRODUTO/SERVIÇO</th>
                                <th class="border-r border-gray-800 p-1 w-20 text-center">QUANT.</th>
                                <th class="p-1 w-32 text-center">VALOR Unit.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in itens" :key="item.id">
                                <tr class="border-b border-gray-400" @click.away="item.dropdown = false">
                                    <td x-show="itens.length > 1" class="border-r border-gray-800 text-center bg-gray-50 print:hidden">
                                        <button type="button" @click="removerItem(index)" class="text-red-600 hover:text-red-800 font-bold text-sm transition-colors cursor-pointer px-1 focus:outline-none" title="Remover este item">
                                            ✕
                                        </button>
                                    </td>
                                    <td class="border-r border-gray-800 text-center text-gray-500 bg-gray-50" x-text="index + 1"></td>
                                    <td class="border-r border-gray-800 p-0 relative">
                                        <input type="text" x-model="item.nome" @input="item.dropdown = true" @focus="item.dropdown = true" class="w-full h-full px-2 py-1 outline-none uppercase focus:bg-yellow-50" autocomplete="off" placeholder="">

                                        <div x-show="item.dropdown && produtosFiltrados(item.nome).length > 0" class="absolute top-full left-0 right-0 bg-white border border-gray-300 shadow-xl z-50 max-h-40 overflow-y-auto print:hidden">
                                            <template x-for="prod in produtosFiltrados(item.nome)">
                                                <div @click="selecionarProduto(index, prod)" class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 text-xs flex justify-between items-center">
                                                    <span class="font-bold" x-text="prod.nome"></span>
                                                    <div class="text-right">
                                                        <span class="text-blue-600 font-bold block">R$ <span x-text="formatarMoeda(prod.preco)"></span></span>
                                                        <span class="text-gray-500 text-[10px]">Estoque: <span x-text="prod.estoque"></span></span>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="border-r border-gray-800 p-0">
                                        <input type="number" min="1" x-model="item.qtd" class="w-full h-full px-1 py-1 text-center outline-none focus:bg-yellow-50" placeholder="">
                                    </td>
                                    <td class="p-0">
                                        <input type="number" step="0.01" min="0" x-model="item.valor" class="w-full h-full px-2 py-1 text-right outline-none focus:bg-yellow-50" placeholder="">
                                    </td>
                                </tr>
                            </template>

                            <tr class="print:hidden">
                                <td :colspan="itens.length > 1 ? 5 : 4">
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
                        <span class="font-bold shrink-0">DESCONTO:</span>
                        <select x-model="tipoDesconto" class="bg-transparent border border-gray-400 rounded px-1 outline-none font-bold text-xs py-0.5 cursor-pointer print:border-none print:appearance-none">
                            <option value="reais">R$</option>
                            <option value="porcentagem">%</option>
                        </select>
                        <input type="number" step="0.01" min="0" x-model="valorDesconto" class="flex-1 min-w-0 p-1 outline-none bg-transparent focus:bg-yellow-100 text-right pr-2" placeholder="">
                    </div>
                    <div class="w-1/2 flex items-center bg-gray-200 px-2 py-1">
                        <span class="font-bold shrink-0 text-slate-900">TOTAL: R$</span>
                        <input type="text" readonly :value="formatarMoeda(totalFinal)" class="flex-1 min-w-0 p-1 outline-none bg-transparent text-right pr-2 font-bold text-xl text-blue-700 cursor-not-allowed print:text-black">
                    </div>
                </div>
            </div>

            <div class="border-2 border-gray-800">
                <div class="bg-gray-200 text-center font-bold border-b-2 border-gray-800 uppercase tracking-widest text-xs py-1">Observações</div>
                <div class="p-2">
                    <textarea class="w-full h-24 outline-none text-xs focus:bg-yellow-50 resize-none" placeholder=""></textarea>
                </div>
            </div>

        </div> </div>

    <div x-show="aba === 'listagem'" class="space-y-4 print:hidden" style="display: none;">
        <div class="bg-white rounded-lg p-6 border-2 border-slate-200 text-slate-800 shadow-sm">
            <h2 class="font-bold text-lg border-b pb-2 mb-4">Histórico de Vendas</h2>
            
            <div class="mb-6 flex flex-col md:flex-row gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Buscar por Cliente</label>
                    <input type="text" x-model="filtroCliente" class="w-full px-3 py-2 border border-slate-300 rounded outline-none text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 uppercase" placeholder="Ex: Nome, CPF...">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wide">Buscar por Produto/Serviço</label>
                    <input type="text" x-model="filtroProduto" class="w-full px-3 py-2 border border-slate-300 rounded outline-none text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 uppercase" placeholder="Ex: Alternador...">
                </div>
                <div class="flex items-end">
                    <button type="button" @click="filtroCliente = ''; filtroProduto = ''" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-600 font-bold rounded text-sm transition-colors cursor-pointer border border-slate-300">
                        Limpar
                    </button>
                </div>
            </div>
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-100 border-b-2 border-gray-300">
                        <th class="p-2">ID</th>
                        <th class="p-2">Cliente</th>
                        <th class="p-2">Total</th>
                        <th class="p-2">Status</th>
                        <th class="p-2 text-right">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="venda in vendasFiltradas" :key="venda.id">
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="p-2 font-bold" x-text="'#' + venda.id"></td>
                            <td class="p-2" x-text="venda.cliente_nome || 'Cliente Padrão'"></td>
                            <td class="p-2 text-blue-700 font-bold" x-text="'R$ ' + formatarMoeda(venda.total)"></td>
                            <td class="p-2">
                                <span x-show="venda.status === 'concluido'" class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold">Concluída</span>
                                <span x-show="venda.status === 'devolvido'" class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-bold">Devolvida</span>
                            </td>
                            <td class="p-2 text-right flex justify-end gap-2">
                                <button @click="abrirDetalhes(venda)" class="bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white px-3 py-1 rounded text-xs font-bold transition-colors cursor-pointer shadow-sm">
                                    Ver Recibo
                                </button>
                                <button x-show="venda.status !== 'devolvido'" @click="registrarDevolucao(venda.id)" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-xs font-bold transition-colors cursor-pointer">
                                    Devolução
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="mostrarModalDetalhes" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 print:p-0 print:block print:bg-transparent print:absolute print:inset-0">
        
        <div class="absolute inset-0 print:hidden" @click="fecharDetalhes()"></div>

        <div class="relative w-full max-w-4xl max-h-screen overflow-y-auto bg-gray-100 p-6 rounded-xl shadow-2xl print:max-h-none print:bg-white print:shadow-none print:rounded-none print:p-0">
            
            <div class="flex justify-between items-center mb-4 print:hidden sticky top-0 bg-gray-100 py-2 z-10 border-b border-gray-300">
                <h3 class="font-bold text-lg text-slate-800">Detalhes do Pedido #<span x-text="vendaSelecionada?.id"></span></h3>
                <div class="flex gap-2">
                    <button type="button" onclick="window.print()" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded text-sm font-bold flex items-center gap-2 cursor-pointer">
                        🖨️ Imprimir
                    </button>
                    <button type="button" @click="fecharDetalhes()" class="bg-red-100 text-red-600 hover:bg-red-600 hover:text-white px-4 py-2 rounded text-sm font-bold transition-colors cursor-pointer">
                        ✕ Fechar
                    </button>
                </div>
            </div>

            <template x-if="vendaSelecionada">
                <div class="w-[210mm] min-h-[297mm] max-w-full mx-auto bg-white p-6 border-2 border-gray-800 text-black font-sans text-sm flex flex-col print:border-none print:w-full print:min-h-0 print:m-0 print:p-0">
                    
                    <div class="flex justify-between items-stretch border-b-2 border-gray-800 pb-4 mb-2 gap-4">
                        <div class="w-1/4 border-2 border-gray-800 flex items-center justify-center p-2 bg-gray-50">
                            <span class="text-xs text-gray-500 text-center">[Logo]</span>
                        </div>
                        <div class="w-2/4 border-2 border-gray-800 p-2 text-center flex flex-col justify-center">
                            <h1 class="font-bold text-lg uppercase">MINHA PÁGINA INICIAL SERVIÇOS</h1>
                            <p class="text-[10px] leading-tight">CPF/CNPJ: 00.000.000/0000-00 - IE: 111111111</p>
                            <p class="text-[10px] leading-tight">Rua Barão de Jaguara, nº 1000</p>
                            <p class="text-[10px] leading-tight">Centro - Campinas - SP - Cep: 13015-001</p>
                            <p class="text-[10px] leading-tight">Tel: (19) 3333-3333 / E-mail: contato@empresa.com.br</p>
                        </div>
                        <div class="w-1/4 border-2 border-gray-800 p-2 flex flex-col items-center justify-center bg-gray-50">
                            <span class="text-xs font-bold uppercase">Nº do Pedido</span>
                            <span class="text-lg font-bold text-slate-800" x-text="vendaSelecionada.id"></span>
                            <span class="text-[10px] text-gray-600" x-text="new Date(vendaSelecionada.data_venda).toLocaleDateString('pt-BR')"></span>
                        </div>
                    </div>

                    <div class="border-2 border-gray-800 mb-2">
                        <div class="bg-gray-200 text-center font-bold border-b-2 border-gray-800 uppercase tracking-widest text-xs py-1">Cliente</div>

                        <div class="flex border-b border-gray-800">
                            <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">NOME</div>
                            <div class="flex-1 px-2 py-1 text-xs uppercase" x-text="vendaSelecionada.cliente_nome || 'Não informado'"></div>
                        </div>

                        <div class="flex border-b border-gray-800">
                            <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">TELEFONE</div>
                            <div class="w-1/3 px-2 py-1 text-xs border-r border-gray-800" x-text="formatarTelefone(vendaSelecionada.cliente_telefone)"></div>
                            <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">EMAIL</div>
                            <div class="flex-1 px-2 py-1 text-xs" x-text="vendaSelecionada.cliente_email"></div>
                        </div>

                        <div class="flex border-b border-gray-800">
                            <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CPF/CNPJ</div>
                            <div class="w-1/2 px-2 py-1 text-xs border-r border-gray-800" x-text="formatarCPFCNPJ(vendaSelecionada.cliente_cpf_cnpj)"></div>
                            <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">RG/IE</div>
                            <div class="flex-1 px-2 py-1 text-xs uppercase" x-text="vendaSelecionada.cliente_rg_ie"></div>
                        </div>

                        <div class="flex border-b border-gray-800">
                            <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">ENDEREÇO</div>
                            <div class="flex-1 px-2 py-1 text-xs uppercase" x-text="vendaSelecionada.cliente_endereco"></div>
                        </div>

                        <div class="flex">
                            <div class="w-20 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">BAIRRO</div>
                            <div class="flex-1 px-2 py-1 text-xs uppercase border-r border-gray-800" x-text="vendaSelecionada.cliente_bairro"></div>
                            <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CIDADE</div>
                            <div class="w-32 px-2 py-1 text-xs uppercase border-r border-gray-800" x-text="vendaSelecionada.cliente_cidade"></div>
                            <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">ESTADO</div>
                            <div class="w-12 px-2 py-1 text-xs uppercase border-r border-gray-800" x-text="vendaSelecionada.cliente_estado"></div>
                            <div class="w-12 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CEP</div>
                            <div class="w-24 px-2 py-1 text-xs" x-text="formatarCEP(vendaSelecionada.cliente_cep)"></div>
                        </div>
                    </div>

                    <div class="border-2 border-gray-800 mb-2 flex-1 flex flex-col">
                        <div class="bg-gray-200 text-center font-bold border-b-2 border-gray-800 uppercase tracking-widest text-xs py-1">Orçamento</div>

                        <div class="flex-1">
                            <table class="w-full text-xs text-left border-collapse relative">
                                <thead>
                                    <tr class="bg-gray-50 border-b-2 border-gray-800">
                                        <th class="border-r border-gray-800 p-1 w-12 text-center">ITEM</th>
                                        <th class="border-r border-gray-800 p-1 pl-2">PRODUTO/SERVIÇO</th>
                                        <th class="border-r border-gray-800 p-1 w-20 text-center">QUANT.</th>
                                        <th class="p-1 w-32 text-center">VALOR Unit.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in vendaSelecionada.itens" :key="index">
                                        <tr class="border-b border-gray-400">
                                            <td class="border-r border-gray-800 text-center text-gray-500 bg-gray-50 py-1" x-text="index + 1"></td>
                                            <td class="border-r border-gray-800 px-2 py-1 uppercase font-medium" x-text="item.nome || item.produto?.nome || 'Serviço Avulso'"></td>
                                            <td class="border-r border-gray-800 px-2 py-1 text-center" x-text="item.quantidade"></td>
                                            <td class="px-2 py-1 text-right" x-text="'R$ ' + formatarMoeda(item.preco_unitario)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="flex border-t-2 border-gray-800 bg-gray-50 text-xs mt-auto">
                            <div class="w-1/2 flex border-r border-gray-800 items-center px-2 py-1 gap-1">
                                <span class="font-bold shrink-0">DESCONTO APLICADO:</span>
                                <span x-show="vendaSelecionada.valor_desconto > 0" class="ml-2 font-medium" x-text="(vendaSelecionada.tipo_desconto === 'porcentagem' ? vendaSelecionada.valor_desconto + '%' : 'R$ ' + formatarMoeda(vendaSelecionada.valor_desconto))"></span>
                                <span x-show="!vendaSelecionada.valor_desconto || vendaSelecionada.valor_desconto == 0" class="ml-2 text-gray-500">Nenhum</span>
                            </div>
                            <div class="w-1/2 flex items-center bg-gray-200 px-2 py-1">
                                <span class="font-bold shrink-0 text-slate-900">TOTAL: R$</span>
                                <div class="flex-1 text-right pr-2 font-bold text-xl text-black" x-text="formatarMoeda(vendaSelecionada.total)"></div>
                            </div>
                        </div>
                    </div>

                    <div class="border-2 border-gray-800">
                        <div class="bg-gray-200 text-center font-bold border-b-2 border-gray-800 uppercase tracking-widest text-xs py-1">Observações</div>
                        <div class="p-2 min-h-[60px] text-xs uppercase" x-text="vendaSelecionada.observacoes || 'Nenhuma observação registrada.'"></div>
                    </div>

                </div>
            </template>
        </div>
    </div>

</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('moduloVendas', () => ({
            aba: 'novo_pedido',
            vendasSalvas: [],

            // --- FUNÇÕES GERAIS ---
            normalizarTexto(texto) {
                if (!texto) return '';
                return texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
            },
            formatarMoeda(valor) {
                let numero = Number(valor) || 0;
                return numero.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            // --- MÁSCARAS DE FORMATAÇÃO ---
            formatarCPFCNPJ(v) {
                if (!v) return '';
                v = v.replace(/\D/g, '');
                if (v.length <= 11) { // CPF
                    v = v.replace(/(\d{3})(\d)/, '$1.$2');
                    v = v.replace(/(\d{3})(\d)/, '$1.$2');
                    v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                } else { // CNPJ
                    v = v.replace(/^(\d{2})(\d)/, '$1.$2');
                    v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
                    v = v.replace(/(\d{4})(\d)/, '$1-$2');
                }
                return v.substring(0, 18);
            },
            formatarTelefone(tel) {
                if (!tel) return '';
                let v = tel.replace(/\D/g, '');
                v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
                if (v.length <= 13) v = v.replace(/(\d{4})(\d)/, '$1-$2'); 
                else v = v.replace(/(\d{5})(\d)/, '$1-$2'); 
                return v.substring(0, 15);
            },
            formatarCEP(cep) {
                if (!cep) return '';
                let v = cep.replace(/\D/g, '');
                v = v.replace(/(\d{5})(\d)/, '$1-$2');
                return v.substring(0, 9);
            },

            // --- LÓGICA DE CLIENTES ---
            buscaCliente: '',
            mostrarDropdownCliente: false,
            cliente: { nome: '', telefone: '', email: '', cpf_cnpj: '', rg_ie: '', endereco: '', bairro: '', cidade: '', estado: '', cep: '' },
            clientesDB: [], 
            
            get clientesFiltrados() {
                if(this.buscaCliente === '') return [];
                
                let buscaNormal = this.normalizarTexto(this.buscaCliente); // Busca texto
                let buscaNum = this.buscaCliente.replace(/\D/g, ''); // Busca só os números

                return this.clientesDB.filter(c => {
                    // Junta nome e e-mail para a busca de texto
                    let nomeEmail = this.normalizarTexto((c.nome || '') + ' ' + (c.email || ''));
                    // Junta CPF e telefone tirando a formatação para a busca de números
                    let docsTels = (c.cpf_cnpj || '').replace(/\D/g, '') + (c.telefone || '').replace(/\D/g, '');
                    
                    return nomeEmail.includes(buscaNormal) || (buscaNum !== '' && docsTels.includes(buscaNum));
                });
            },
            selecionarCliente(c) {
                this.buscaCliente = c.nome;
                this.cliente = { ...c };
                this.mostrarDropdownCliente = false;
            },

            // --- LÓGICA DE PRODUTOS E ITENS ---
            itens: [
                { id: Date.now(), produto_id: null, nome: '', qtd: '1', valor: '', dropdown: false }
            ],
            adicionarItem() {
                this.itens.push({ id: Date.now(), produto_id: null, nome: '', qtd: '1', valor: '', dropdown: false });
            },
            removerItem(index) {
                if (this.itens.length > 1) {
                    this.itens.splice(index, 1);
                }
            },
            
            // Injeta os produtos reais direto do banco de dados
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

            // --- LÓGICA DE BUSCA E MODAL DO HISTÓRICO ---
            filtroCliente: '',
            filtroProduto: '',
            mostrarModalDetalhes: false,
            vendaSelecionada: null,

            get vendasFiltradas() {
                let lista = this.vendasSalvas;
                
                // 1. FILTRO DE CLIENTE (Agora busca por Nome, CPF/CNPJ, Telefone e Email)
                if (this.filtroCliente.trim() !== '') {
                    let buscaNormal = this.normalizarTexto(this.filtroCliente);
                    let buscaNum = this.filtroCliente.replace(/\D/g, '');

                    lista = lista.filter(v => {
                        let nomeEmail = this.normalizarTexto((v.cliente_nome || '') + ' ' + (v.cliente_email || ''));
                        let docsTels = (v.cliente_cpf_cnpj || '').replace(/\D/g, '') + (v.cliente_telefone || '').replace(/\D/g, '');
                        
                        return nomeEmail.includes(buscaNormal) || (buscaNum !== '' && docsTels.includes(buscaNum));
                    });
                }
                
                // 2. FILTRO DE PRODUTO
                if (this.filtroProduto.trim() !== '') {
                    let buscaProd = this.normalizarTexto(this.filtroProduto);
                    lista = lista.filter(v => {
                        if (!v.itens || v.itens.length === 0) return false;
                        return v.itens.some(item => this.normalizarTexto(item.produto?.nome || item.nome || '').includes(buscaProd));
                    });
                }
                return lista;
            },

            abrirDetalhes(venda) {
                this.vendaSelecionada = venda;
                this.mostrarModalDetalhes = true;
            },
            fecharDetalhes() {
                this.mostrarModalDetalhes = false;
                this.vendaSelecionada = null;
            },

            // --- INTEGRAÇÃO COM O BACKEND (LARAVEL) ---
            // --- INTEGRAÇÃO COM O BACKEND (LARAVEL) ---
        async finalizarVenda() {
            // Garante que o nome do cliente vai no objeto, mesmo se for digitado manualmente
            if (!this.cliente.nome && this.buscaCliente) {
                this.cliente.nome = this.buscaCliente;
            }

            // --- NOVA VALIDAÇÃO DE SEGURANÇA DO CLIENTE ---
            if (!this.cliente.nome || this.cliente.nome.trim() === '') {
                return alert('Erro: É obrigatório preencher o NOME do cliente para prosseguir!');
            }

            if (!this.cliente.cpf_cnpj || this.cliente.cpf_cnpj.trim() === '') {
                return alert('Erro: É obrigatório preencher o CPF/CNPJ do cliente!');
            }

            // Verifica se tem e-mail OU telefone preenchido
            let temEmail = this.cliente.email && this.cliente.email.trim() !== '';
            let temTelefone = this.cliente.telefone && this.cliente.telefone.trim() !== '';

            if (!temEmail && !temTelefone) {
                return alert('Erro: Você precisa informar pelo menos um meio de contato (E-MAIL ou TELEFONE)!');
            }

            // --- VALIDAÇÃO DOS ITENS ---
            if(!this.itens[0].nome) {
                return alert('Preencha pelo menos o nome do primeiro produto ou serviço!');
            }

            let payload = {
                buscaCliente: this.buscaCliente,
                cliente: this.cliente,
                itens: this.itens,
                subtotal: this.subtotal,
                valorDesconto: this.valorDesconto || 0,
                tipoDesconto: this.tipoDesconto,
                totalFinal: this.totalFinal
            };

            try {
                let response = await fetch('/api/vendas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content
                    },
                    body: JSON.stringify(payload)
                });

                let data = await response.json();

                if(response.ok) {
                    alert('Venda Nº ' + (data.id || '') + ' guardada com sucesso!');
                    this.carregarVendas();
                    this.aba = 'listagem';
                    
                    // Limpa o formulário após sucesso
                    this.itens = [{ id: Date.now(), produto_id: null, nome: '', qtd: '1', valor: '', dropdown: false }];
                    this.valorDesconto = '';
                    this.buscaCliente = '';
                    this.cliente = { nome: '', telefone: '', email: '', cpf_cnpj: '', rg_ie: '', endereco: '', bairro: '', cidade: '', estado: '', cep: '' };
                } else {
                    alert('Erro ao guardar: ' + (data.message || data.error || 'Verifique os dados enviados.'));
                    console.error("Detalhes do erro:", data);
                }
                
            } catch(e) {
                alert('Falha de ligação com o servidor. Verifique a consola.');
                console.error(e);
            }
        },

            async carregarVendas() {
                try {
                    let response = await fetch('/api/vendas');
                    if (response.ok) {
                        this.vendasSalvas = await response.json();
                    }
                } catch(e) {
                    console.error('Erro ao carregar vendas:', e);
                }
            },

            async registrarDevolucao(id) {
                if(confirm('Tem certeza que deseja registrar a devolução? As peças voltarão ao estoque.')) {
                    try {
                        let response = await fetch(`/api/vendas/${id}/devolver`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content }
                        });

                        if(response.ok) {
                            alert('Devolução registrada com sucesso!');
                            this.carregarVendas();
                        }
                    } catch(e) {
                        console.error('Erro na devolução:', e);
                    }
                }
            },

            init() {
                this.carregarVendas(); 
            }
        }));
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection