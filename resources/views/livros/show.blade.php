@extends('layouts.app')

@section('content')
@if(session('error'))
    <div class="alert alert-error mb-3">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success mb-3">
        {{ session('success') }}
    </div>
@endif

<div class="flex gap-6">
    <img src="{{ $livro->capa_url ? asset('storage/'.$livro->capa_url) : 'https://via.placeholder.com/200' }}"
         class="w-48 h-64 object-cover rounded">

    <div>
        <h1 class="text-4xl font-bold">{{ $livro->nome }}</h1>

        <p><strong>ISBN:</strong> {{ $livro->isbn }}</p>
        <p><strong>Editora:</strong> {{ $livro->editora->nome }}</p>
        <p><strong>Autores:</strong>
            {{ $livro->autores->pluck('nome')->join(', ') }}
        </p>

        <p class="mt-3">{{ $livro->bibliografia }}</p>

        <p class="mt-4">
            <span class="badge {{ $livro->disponivel ? 'badge-success' : 'badge-error' }}">
                {{ $livro->disponivel ? 'Disponível' : 'Requisitado' }}
            </span>
        </p>

        @if($livro->disponivel)
        <form action="{{ route('requisicoes.store') }}" method="POST" class="mt-4">
            @csrf
            <input type="hidden" name="livro_id" value="{{ $livro->id }}">
            <button class="btn btn-primary">📚 Requisitar</button>
        </form>
        @else
        <p class="text-red-700 mt-4">Este livro está requisitado no momento.</p>
        @endif
    </div>
</div>
<div class="mt-6">
    <a href="{{ route('livros.index') }}" class="btn btn-secondary">
        ⬅ Voltar
    </a>
</div>

@endsection
