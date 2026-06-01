<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque de Peças - AutoPeças</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-8 font-sans">

    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Catálogo & Estoque de Peças</h2>
                <p class="text-slate-500 text-sm mt-1">Controle de inventário, localizações e aplicações veiculares.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 px-4 py-2 rounded-lg font-medium transition-colors shadow-sm text-sm">
                    Voltar ao Dashboard
                </a>
                <a href="#" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Cadastrar Nova Peça
                </a>
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
                            <th scope="col" class="px-6 py-4">Aplicação (RF05)</th>
                            <th scope="col" class="px-6 py-4">Localização (RF01)</th>
                            <th scope="col" class="px-6 py-4">Qtd. Estoque (RF04)</th>
                            <th scope="col" class="px-6 py-4">Preço Venda (RF08)</th>
                            <th scope="col" class="px-6 py-4 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                <span class="block font-bold text-slate-700">#ALT-2041</span>
                                <span>7891234567890</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">Alternador 12V 90A</div>
                                <div class="text-xs text-slate-400">Marca: Bosch</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded">Fox 1.0 / Gol G6</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700">Corredor A - Prateleira 3</td>
                            <td class="px-6 py-4">
                                <div class="text-slate-800 font-semibold">15 unidades</div>
                                <div class="text-xs text-slate-400">Mínimo: 5</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-900">R$ 489,90</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Editar</a>
                            </td>
                        </tr>

                        <tr class="bg-amber-50/40 hover:bg-amber-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                <span class="block font-bold text-slate-700">#MOT-0912</span>
                                <span>7890001112223</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">Motor de Partida ZM</div>
                                <div class="text-xs text-slate-400">Marca: ZM SA</div>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded">Civic 1.8 2012+</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700">Corredor C - Gaveta 12</td>
                            <td class="px-6 py-4">
                                <div class="text-red-600 font-bold flex items-center gap-1">
                                    2 unidades
                                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                                </div>
                                <div class="text-xs text-amber-700 font-medium">Mínimo: 4 (Comprar!)</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-900">R$ 620,00</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium text-xs">Editar</a>
                            </td>
                        </tr>

                        </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-between items-center text-xs text-slate-500">
                <span>Mostrando 2 de 2 produtos cadastrados</span>
                <div class="flex gap-1">
                    <button class="px-3 py-1 border rounded bg-white text-slate-400 cursor-not-allowed">Anterior</button>
                    <button class="px-3 py-1 border rounded bg-white text-slate-700 hover:bg-slate-50">Próximo</button>
                </div>
                {{-- {{ $produtos->links() }} --}}
            </div>
        </div>

    </div>

</body>
</html>