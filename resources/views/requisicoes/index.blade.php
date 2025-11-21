@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto mt-6">

    <h1 class="text-3xl font-bold mb-6">🔁 Minhas Requisições</h1>

    {{-- Alerts --}}
    @include('components.alertas')

    {{-- Tabs --}}
    <div class="tabs mb-4">
        <a href="#ativas" class="tab tab-bordered tab-active">Ativas</a>
        <a href="#entregues" class="tab tab-bordered">Entregues</a>
        <a href="#canceladas" class="tab tab-bordered">Canceladas</a>
    </div>

    {{-- Conteúdo das Tabs --}}
    <div id="ativas" class="tab-content">
        @include('requisicoes.partials.tabela', ['lista' => $ativas, 'titulo' => 'Requisições Ativas'])
    </div>

    <div id="entregues" class="tab-content hidden">
        @include('requisicoes.partials.tabela', ['lista' => $entregues, 'titulo' => 'Requisições Entregues'])
    </div>

    <div id="canceladas" class="tab-content hidden">
        @include('requisicoes.partials.tabela', ['lista' => $canceladas, 'titulo' => 'Requisições Canceladas'])
    </div>

</div>

{{-- Script para alternar abas --}}
<script>
document.querySelectorAll('.tabs a').forEach((btn, index) => {
    btn.addEventListener('click', () => {
        
        // Remove active tab class
        document.querySelectorAll('.tabs a').forEach(el => el.classList.remove('tab-active'));
        btn.classList.add('tab-active');
        
        // Show correct content
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-content')[index].classList.remove('hidden');
    });
});
</script>

@endsection
