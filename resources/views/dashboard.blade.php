@extends('layouts.app')

@section('titulo_pagina', 'Visão Geral')

@section('conteudo')
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-slate-800">Bem-vindo de volta!</h3>
        <p class="text-slate-500 mt-1">Aqui está o resumo do seu estoque e vendas hoje.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4">
            <div class="p-4 bg-blue-50 text-blue-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total de Peças</p>
                <h4 class="text-2xl font-bold text-slate-800">{{ number_format($totalProdutos, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4">
            <div class="p-4 bg-red-50 text-red-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Estoque Crítico</p>
                <h4 class="text-2xl font-bold text-red-600">{{ $estoqueBaixo }}</h4>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4">
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Vendas Hoje</p>
                <h4 class="text-2xl font-bold text-slate-800">R$ {{ number_format($vendasHoje, 2, ',', '.') }}</h4>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4">
            <div class="p-4 bg-green-50 text-green-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402-2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Vendas do Mês</p>
                <h4 class="text-2xl font-bold text-slate-800">R$ {{ number_format($vendasMes, 2, ',', '.') }}</h4>
            </div>
        </div>

    </div>
@endsection