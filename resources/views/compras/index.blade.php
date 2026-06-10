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

<div x-data="{
    aba: 'novo_pedido',
    comprasSalvas: [], 
    
    normalizarTexto(texto) {
        if (!texto) return '';
        return texto.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toUpperCase();
    },
    formatarMoeda(valor) {
        let numero = Number(valor) || 0;
        return numero.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },
    
    // --- LÓGICA DE FORNECEDORES (COM DADOS DO SEU BANCO) ---
    buscaFornecedor: '',
    mostrarDropdownFornecedor: false,
    fornecedor: { id: null, nome_fantasia: '', razao_social: '', cnpj: '', telefone: '', email: '' },
    
    // Injeta os dados do seu CompraController diretamente aqui e mapeia um campo 'nome' para facilitar a busca
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
        this.fornecedor = { ...f };
        this.mostrarDropdownFornecedor = false;
    },

    // --- LÓGICA DE PRODUTOS E ITENS (COM DADOS DO SEU BANCO) ---
    itens: [
        { id: Date.now(), produto_id: null, nome: '', qtd: '1', valor: '', dropdown: false }
    ],
    
    adicionarItem() {
        this.itens.push({ id: Date.now(), produto_id: null, nome: '', qtd: '1', valor: '', dropdown: false });
    },
    
    // Injeta os produtos que vieram do Controller
    produtosDB: @json($produtos),
    
    produtosFiltrados(busca) {
        if(busca === '') return [];
        let buscaNorm = this.normalizarTexto(busca);
        return this.produtosDB.filter(p => this.normalizarTexto(p.nome).includes(buscaNorm));
    },
    
    selecionarProduto(index, prod) {
        this.itens[index].produto_id = prod.id; 
        this.itens[index].nome = prod.nome;
        // O seu banco retorna 'preco', mas usamos 'valor' no input
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

    // --- INTEGRAÇÃO COM SEU COMPRACONTROLLER ---
    async finalizarCompra() {
        if(!this.fornecedor.id) {
            return alert('Por favor, selecione um fornecedor válido da lista!');
        }
        if(!this.itens[0].nome) {
            return alert('Adicione pelo menos um produto!');
        }

        // Montamos o payload EXATAMENTE como seu store(Request $request) espera
        let payload = {
            fornecedor_id: this.fornecedor.id,
            total: this.totalFinal,
            itens: this.itens
        };

        try {
            // Ajuste a rota '/compras' se a sua rota POST no web.php for diferente
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
                // Checa a genialidade que você fez no Controller: se tiver URL de redirecionamento, envia para lá!
                if(data.redirecionar_para) {
                    alert('Compra salva! Você adicionou um produto novo. Redirecionando para complementar o cadastro da peça...');
                    window.location.href = data.redirecionar_para;
                } else {
                    alert('Compra salva com sucesso! Estoque atualizado.');
                    this.carregarCompras();
                    this.aba = 'listagem';
                    // Reseta o form
                    this.itens = [{ id: Date.now(), produto_id: null, nome: '', qtd: '1', valor: '', dropdown: false }];
                    this.valorDesconto = '';
                    this.buscaFornecedor = '';
                    this.fornecedor = { id: null };
                }
            } else {
                alert('Erro ao salvar: ' + (data.error || 'Erro desconhecido no servidor.'));
            }
        } catch(e) {
            console.error('Erro de requisição:', e);
            alert('Falha ao se comunicar com o servidor.');
        }
    },

    async carregarCompras() {
        try {
            // Rota que aponta para o seu public function listarAPI()
            let response = await fetch('/api/compras/listar');
            if(response.ok) {
                this.comprasSalvas = await response.json();
            }
        } catch(e) {
            console.error('Não foi possível carregar o histórico', e);
        }
    },

    init() {
        this.carregarCompras(); 
    }
}" class="w-full">

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
                    <input type="text" x-model="buscaFornecedor" @input="mostrarDropdownFornecedor = true; fornecedor.cnpj = ''; fornecedor.telefone = ''; fornecedor.cidade = '';" @focus="mostrarDropdownFornecedor = true" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50" autocomplete="off" placeholder="Digite para buscar...">

                    <div x-show="mostrarDropdownFornecedor && fornecedoresFiltrados.length > 0" x-transition class="absolute top-full left-24 right-0 bg-white border border-gray-300 shadow-xl z-50 max-h-40 overflow-y-auto print:hidden">
                        <template x-for="f in fornecedoresFiltrados" :key="f.cnpj">
                            <div @click="selecionarFornecedor(f)" class="px-3 py-2 hover:bg-blue-50 cursor-pointer border-b border-gray-100 text-xs">
                                <span class="font-bold block" x-text="f.nome_exibicao"></span>
                                <span class="text-gray-500 text-[10px]" x-text="'CNPJ: ' + (f.cnpj || 'N/A') + ' | Cidade: ' + (f.cidade || 'N/A')"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex border-b border-gray-800">
                    <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">CNPJ</div>
                    <input type="text" x-model="fornecedor.cnpj" class="w-1/2 min-w-0 px-2 py-1 outline-none text-xs focus:bg-yellow-50 border-r border-gray-800">
                    <div class="w-24 shrink-0 font-bold border-r border-gray-800 px-2 py-1 text-xs bg-gray-50 flex items-center">TELEFONE</div>
                    <input type="text" x-model="fornecedor.telefone" class="flex-1 min-w-0 px-2 py-1 outline-none text-xs uppercase focus:bg-yellow-50">
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

    </div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection