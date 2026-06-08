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

<div x-data="{
    aba: 'novo_pedido',
    normalizarTexto(texto) {
        if (!texto) return '';
        return texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
    },
    formatarMoeda(valor) {
        let numero = Number(valor) || 0;
        return numero.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
    // --- LÓGICA DE CLIENTES ---
    buscaCliente: '',
    mostrarDropdownCliente: false,
    cliente: { telefone: '', email: '', cpf_cnpj: '', rg_ie: '', endereco: '', bairro: '', cidade: '', estado: '', cep: '' },
    clientesDB: [],
    get clientesFiltrados() {
        if(this.buscaCliente === '') return [];
        let busca = this.normalizarTexto(this.buscaCliente);
        return this.clientesDB.filter(c => this.normalizarTexto(c.nome).startsWith(busca));
    },
    selecionarCliente(c) {
        this.buscaCliente = c.nome;
        this.cliente = { ...c };
        this.mostrarDropdownCliente = false;
    },
    // --- LÓGICA DE PRODUTOS E ITENS ---
    itens: [
        { id: Date.now(), nome: '', qtd: '', valor: '', dropdown: false }
    ],
    adicionarItem() {
        this.itens.push({ id: Date.now(), nome: '', qtd: '', valor: '', dropdown: false });
    },
    produtosDB: [],
    produtosFiltrados(busca) {
        if(busca === '') return [];
        let buscaNorm = this.normalizarTexto(busca);
        return this.produtosDB.filter(p => this.normalizarTexto(p.nome).startsWith(buscaNorm));
    },
    selecionarProduto(index, prod) {
        this.itens[index].nome = prod.nome;
        this.itens[index].valor = prod.valor;
        this.itens[index].dropdown = false;
    },
    // --- LÓGICA DE MATEMÁTICA ---
    tipoDesconto: 'reais',
    valorDesconto: '',
    get subtotal() {
        return this.itens.reduce((soma, item) => {
            let q = Number(item.qtd) || 0;
            let v = Number(item.valor) || 0;
            return soma + (q * v);
        }, 0);
    },
    get totalFinal() {
        let sub = this.subtotal;
        let desc = Number(this.valorDesconto) || 0;
        if (this.tipoDesconto === 'porcentagem') {
            let calc = sub - (sub * (desc / 100));
            return calc > 0 ? calc : 0;
        }
        let calc = sub - desc;
        return calc > 0 ? calc : 0;
    }
}" class="w-full">

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
            <button @click="alert('Irá salvar no banco de dados e gerar o ID do pedido.')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded flex items-center gap-2 transition-colors cursor-pointer shadow-sm">
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
                    <input type="text" x-model="buscaCliente" @input="mostrarDropdownCliente = true; cliente.telefone = ''; cliente.email = ''; cliente.cpf_cnpj = ''; cliente.rg_ie = ''; cliente.endereco = ''; cliente.bairro = ''; cliente.cidade = ''; cliente.estado = ''; cliente.cep = '';" @focus="mostrarDropdownCliente = true" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50" autocomplete="off" placeholder="">

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
                    <input type="text" x-model="cliente.telefone" @input="cliente.telefone = cliente.telefone.replace(/[^0-9]/g, '')" class="w-1/3 min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50 border-r border-gray-800">
                    <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">EMAIL</div>
                    <input type="email" x-model="cliente.email" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50">
                </div>

                <div class="flex border-b border-gray-800">
                    <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CPF/CNPJ</div>
                    <input type="text" x-model="cliente.cpf_cnpj" @input="cliente.cpf_cnpj = cliente.cpf_cnpj.replace(/[^0-9]/g, '')" class="w-1/2 min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50 border-r border-gray-800">
                    <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">RG/IE</div>
                    <input type="text" x-model="cliente.rg_ie" @input="cliente.rg_ie = cliente.rg_ie.replace(/[^0-9xX]/g, '')" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50">
                </div>

                <div class="flex border-b border-gray-800">
                    <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">ENDEREÇO</div>
                    <input type="text" x-model="cliente.endereco" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50">
                </div>

                <div class="flex">
                    <div class="w-20 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">BAIRRO</div>
                    <input type="text" x-model="cliente.bairro" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50 border-r border-gray-800">
                    <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CIDADE</div>
                    <input type="text" x-model="cliente.cidade" class="w-32 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50 border-r border-gray-800">
                    <div class="w-16 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">ESTADO</div>
                    <input type="text" x-model="cliente.estado" class="w-12 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50 border-r border-gray-800">
                    <div class="w-12 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CEP</div>
                    <input type="text" x-model="cliente.cep" @input="cliente.cep = cliente.cep.replace(/[^0-9]/g, '')" class="w-24 min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50">
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
                            <template x-for="(item, index) in itens" :key="item.id">
                                <tr class="border-b border-gray-400" @click.away="item.dropdown = false">
                                    <td class="border-r border-gray-800 text-center text-gray-500 bg-gray-50" x-text="index + 1"></td>
                                    <td class="border-r border-gray-800 p-0 relative">
                                        <input type="text" x-model="item.nome" @input="item.dropdown = true" @focus="item.dropdown = true" class="w-full h-full px-2 py-1 outline-none uppercase focus:bg-yellow-50" autocomplete="off" placeholder="">

                                        <div x-show="item.dropdown && produtosFiltrados(item.nome).length > 0" class="absolute top-full left-0 right-0 bg-white border border-gray-300 shadow-xl z-50 max-h-40 overflow-y-auto print:hidden">
                                            <template x-for="prod in produtosFiltrados(item.nome)">
                                                <div @click="selecionarProduto(index, prod)" class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 text-xs flex justify-between">
                                                    <span class="font-bold" x-text="prod.nome"></span>
                                                    <span class="text-blue-600 font-medium">R$ <span x-text="formatarMoeda(prod.valor)"></span></span>
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
                                <td colspan="4" class="p-1">
                                    <button @click="adicionarItem()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-1 rounded text-xs border border-dashed border-slate-300 transition-colors">
                                        + Adicionar Linha
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
        <div class="bg-white rounded-lg p-4 border border-slate-200 text-center text-slate-500">
            A listagem de histórico entrará aqui no futuro.
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection