@extends('layouts.app')

@section('titulo_pagina', 'Administração')

@section('conteudo')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-slate-50">

        <div class="p-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Painel de Administração</h3>
                    <p class="text-slate-500 mt-1">Gerencie os acessos ao sistema e o cadastro de parceiros comerciais.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="flex flex-col gap-4">
                    <a href="{{ route('usuarios.create') }}" class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-blue-500 hover:shadow-md transition-all flex flex-col items-center text-center flex-1">
                        <div class="p-4 bg-blue-50 text-blue-600 rounded-full mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-800 mb-1">Novo Usuário</h4>
                        <p class="text-sm text-slate-500">Cadastre um novo membro e envie um convite de acesso.</p>
                    </a>

                    <a href="{{ route('perfis.index') }}" class="group bg-white p-4 rounded-xl shadow-sm border border-slate-200 hover:border-orange-500 hover:shadow-md transition-all flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div class="text-left">
                                <h4 class="text-sm font-bold text-slate-800">Gerenciar Perfis</h4>
                                <p class="text-xs text-slate-500">Permissões e regras</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <a href="{{ route('usuarios.lista') }}" class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-indigo-500 hover:shadow-md transition-all flex flex-col items-center text-center">
                    <div class="p-4 bg-indigo-50 text-indigo-600 rounded-full mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-1">Lista de Usuários</h4>
                    <p class="text-sm text-slate-500">Visualize todos os usuários ativos e inativos no sistema.</p>
                </a>

                <a href="{{ route('fornecedores.index') }}" class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:border-emerald-500 hover:shadow-md transition-all flex flex-col items-center text-center">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-full mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-800 mb-1">Cadastrar Fornecedor</h4>
                    <p class="text-sm text-slate-500">Gerencie as empresas parceiras que fornecem peças e estoque.</p>
                </a>

            </div>
        </div>
    </main>
@endsection