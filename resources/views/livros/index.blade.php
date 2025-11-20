@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-4">📚 Catálogo de Livros</h1>

<div class="grid md:grid-cols-3 gap-6">
@foreach($livros as $livro)
    <div class="card bg-base-200 shadow-xl">
        <figure>
            <img src="{{ $livro->capa_url ? asset('storage/'.$livro->capa_url) : 'https://via.placeholder.com/150' }}"
                 class="h-48 w-full object-cover">
        </figure>
        <div class="card-body">
            <h2 class="card-title">{{ $livro->nome }}</h2>

            <p><strong>Editora:</strong> {{ $livro->editora->nome }}</p>

            <a href="{{ route('livros.show', $livro->id) }}" class="btn btn-primary mt-2">
                Detalhes 📖
            </a>
        </div>
    </div>
@endforeach
</div>
@endsection
