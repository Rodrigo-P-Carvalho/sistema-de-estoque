@extends('layouts.app')

@section('titulo_pagina', 'Fornecedores')

@section('conteudo')
    <div class="mb-6">
        <h3 class="text-2xl font-bold text-slate-800">Gerenciar Fornecedores</h3>
        <p class="text-slate-500 mt-1">Cadastre novos parceiros e visualize a lista de fornecedores ativos.</p>
    </div>

    @if(session('sucesso'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg relative mb-6 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium text-sm">{{ session('sucesso') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden sticky top-6">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h4 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Novo Fornecedor
                </h4>
            </div>
            
            <form action="{{ route('fornecedores.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Razão Social</label>
                        <input type="text" name="razao_social" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm transition-all" placeholder="Nome oficial da empresa" value="{{ old('razao_social') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nome Fantasia</label>
                        <input type="text" name="nome_fantasia" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm transition-all" placeholder="Como é conhecida" value="{{ old('nome_fantasia') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">CNPJ</label>
                        <input type="text" name="cnpj" id="cnpj" maxlength="18" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm transition-all" placeholder="00.000.000/0000-00" value="{{ old('cnpj') }}">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Telefone</label>
                            <input type="text" name="telefone" id="telefone" maxlength="15" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm transition-all" placeholder="(00) 00000-0000" value="{{ old('telefone') }}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">E-mail</label>
                            <input type="email" name="email" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm transition-all" placeholder="email@empresa.com" value="{{ old('email') }}">
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg font-medium text-sm transition-colors shadow-sm flex justify-center items-center gap-2">
                        Salvar Fornecedor
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                <h4 class="text-lg font-bold text-slate-800">Fornecedores Cadastrados</h4>
                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full">{{ $fornecedores->count() }} registros</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-sm text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-3 font-medium">Empresa</th>
                            <th class="px-6 py-3 font-medium">CNPJ</th>
                            <th class="px-6 py-3 font-medium">Contatos</th>
                            <th class="px-6 py-3 font-medium text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($fornecedores as $fornecedor)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">{{ $fornecedor->nome_fantasia ?: $fornecedor->razao_social }}</div>
                                    <div class="text-xs text-slate-500">{{ $fornecedor->razao_social }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $fornecedor->cnpj_formatado }}</td>
                                <td class="px-6 py-4">
                                    @if($fornecedor->telefone_formatado)
                                        <div class="text-slate-800 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $fornecedor->telefone_formatado }}
                                        </div>
                                    @endif
                                    
                                    @if($fornecedor->email)
                                        <div class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $fornecedor->email }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button type="button" onclick="abrirModalEdicao({{ $fornecedor->id }})" class="text-blue-600 hover:text-blue-800 font-medium text-sm bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <p class="font-medium text-slate-600">Nenhum fornecedor cadastrado.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalEdicao" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex justify-center items-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-lg overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                <h4 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar Fornecedor
                </h4>
                <button onclick="fecharModalEdicao()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="formEdicao" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Razão Social</label>
                        <input type="text" name="razao_social" id="edit_razao_social" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nome Fantasia</label>
                        <input type="text" name="nome_fantasia" id="edit_nome_fantasia" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">CNPJ</label>
                        <input type="text" name="cnpj" id="edit_cnpj" maxlength="18" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Telefone</label>
                            <input type="text" name="telefone" id="edit_telefone" maxlength="15" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">E-mail</label>
                            <input type="email" name="email" id="edit_email" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="fecharModalEdicao()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg font-medium text-sm transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Função para formatar strings diretamente (usada para preencher o modal)
        function aplicarMascaraCNPJ(valor) {
            if(!valor) return '';
            let v = valor.replace(/\D/g, '');
            v = v.replace(/^(\d{2})(\d)/, '$1.$2');
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
            v = v.replace(/(\d{4})(\d)/, '$1-$2');
            return v;
        }

        function aplicarMascaraTelefone(valor) {
            if(!valor) return '';
            let v = valor.replace(/\D/g, '');
            v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
            if (v.length > 9) {
                v = v.replace(/(\d{5})(\d)/, '$1-$2');
            } else {
                v = v.replace(/(\d{4})(\d)/, '$1-$2');
            }
            return v;
        }

        // CONTROLADORES DO MODAL VIA AJAX
        function abrirModalEdicao(id) {
            // Busca os dados do fornecedor via AJAX usando a rota que criamos
            fetch(`/administracao/fornecedores/${id}/editar`)
                .then(response => response.json())
                .then(data => {
                    // Preenche os campos do Modal
                    document.getElementById('edit_razao_social').value = data.razao_social || '';
                    document.getElementById('edit_nome_fantasia').value = data.nome_fantasia || '';
                    
                    // Preenche aplicando a formatação visual
                    document.getElementById('edit_cnpj').value = aplicarMascaraCNPJ(data.cnpj);
                    document.getElementById('edit_telefone').value = aplicarMascaraTelefone(data.telefone);
                    document.getElementById('edit_email').value = data.email || '';

                    // Define dinamicamente para qual URL o formulário vai enviar o PUT
                    document.getElementById('formEdicao').action = `/administracao/fornecedores/${id}`;

                    // Exibe o modal na tela retirando a classe hidden
                    document.getElementById('modalEdicao').classList.remove('hidden');
                })
                .catch(error => alert('Erro ao carregar os dados do fornecedor.'));
        }

        function fecharModalEdicao() {
            document.getElementById('modalEdicao').classList.add('hidden');
        }

        // CONFIGURAÇÃO DOS EVENTOS DE DIGITAÇÃO (MÁSCARAS EM TEMPO REAL)
        
        // Elementos do formulário de Cadastro
        const cnpjInput = document.getElementById('cnpj');
        const telefoneInput = document.getElementById('telefone');
        
        // Elementos do formulário do Modal de Edição
        const editCnpjInput = document.getElementById('edit_cnpj');
        const editTelefoneInput = document.getElementById('edit_telefone');

        // Ouvintes para o formulário de Cadastro
        cnpjInput.addEventListener('input', (e) => e.target.value = aplicarMascaraCNPJ(e.target.value));
        telefoneInput.addEventListener('input', (e) => e.target.value = aplicarMascaraTelefone(e.target.value));

        // Ouvintes para o formulário do Modal de Edição
        editCnpjInput.addEventListener('input', (e) => e.target.value = aplicarMascaraCNPJ(e.target.value));
        editTelefoneInput.addEventListener('input', (e) => e.target.value = aplicarMascaraTelefone(e.target.value));
    </script>
@endsection