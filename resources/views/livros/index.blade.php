@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')

<div class="max-w-6xl mx-auto mt-10">

    {{-- Título --}}
    <h1 class="text-4xl font-bold mb-8 text-center">
        📚 Catálogo de Livros
    </h1>

    {{-- Barra de pesquisa --}}
    <form method="GET" action="{{ route('livros.index') }}" class="w-full mb-8">
        <input type="text" name="q" value="{{ request('q') }}"
            placeholder="Pesquisar por título, autor, editora ou ISBN..."
            class="input input-bordered w-full shadow-md" />
    </form>

    @include('components.alertas')

    {{-- GRID MODERNO --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @foreach($livros as $livro)
            <div class="bg-white shadow-lg rounded-xl p-4 hover:shadow-2xl transition border border-gray-200">

                {{-- Capa --}}
                @php
                    $capa = $livro->capa_url
                        ? asset('storage/' . $livro->capa_url)
                        : asset('storage/images/placeholders/book-placeholder.png');
                @endphp

                <img src="{{ $capa }}" alt="{{ $livro->nome }}"
                     class="w-full h-64 object-cover rounded-lg shadow">

                <div class="mt-4 space-y-1">

                    {{-- Título --}}
                    <h2 class="text-xl font-semibold text-gray-900 line-clamp-2">
                        {{ $livro->nome }}
                    </h2>

                    {{-- Autor(es) --}}
                    <p class="text-gray-700 text-sm">
                        <strong>Autor:</strong>
                        {{ $livro->autores->pluck('nome')->join(', ') }}
                    </p>

                    {{-- Editora --}}
                    <p class="text-gray-700 text-sm">
                        <strong>Editora:</strong> {{ $livro->editora->nome }}
                    </p>

                    {{-- ISBN --}}
                    <p class="text-gray-700 text-sm">
                        <strong>ISBN:</strong> {{ $livro->isbn }}
                    </p>

                    {{-- Disponibilidade --}}
                    <span class="badge mt-2 {{ $livro->disponivel ? 'badge-success' : 'badge-error' }}">
                        {{ $livro->disponivel ? 'Disponível' : 'Requisitado' }}
                    </span>
                </div>

                {{-- Botão --}}
                <a href="{{ route('livros.show', $livro) }}"
                   class="btn btn-secondary w-full mt-4 shadow">
                    Ver detalhes
                </a>

            </div>
        @endforeach
    </div>

    {{-- Paginação --}}
    <div class="mt-10">
        {{ $livros->links() }}
    </div>

</div>

<footer class="footer sm:footer-horizontal footer-center mt-16">
    <aside>
        <p>Copyright © All right reserved by Inovcorp Group</p>
    </aside>
</footer>

@endsection
