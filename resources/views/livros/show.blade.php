@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto mt-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row gap-6 bg-white p-6 rounded-lg shadow">

        {{-- Capa --}}
        <img src="{{ $livro->capa_url ? asset('storage/'.$livro->capa_url) : 'https://via.placeholder.com/150' }}"
             class="w-40 h-56 object-cover rounded shadow">

        {{-- Info Principal --}}
        <div class="flex-1">
            <h1 class="text-3xl font-bold mb-1">{{ $livro->nome }}</h1>

            <p class="text-gray-600 mb-2">
                <strong>Autor(es):</strong> {{ $livro->autores->pluck('nome')->join(', ') }}
            </p>

            <p class="text-gray-600 mb-2">
                <strong>Editora:</strong> {{ $livro->editora->nome }}
            </p>

            <p class="text-gray-600 mb-4">
                <strong>ISBN:</strong> {{ $livro->isbn }}
            </p>

            {{-- Badge Estado --}}
            <span class="badge {{ $livro->disponivel ? 'badge-success' : 'badge-error' }}">
                {{ $livro->disponivel ? 'Disponível' : 'Requisitado' }}
            </span>

            {{-- Botão Requisição --}}
            @auth
                @if($livro->disponivel)
                    <form action="{{ route('requisicoes.store') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="livro_id" value="{{ $livro->id }}">
                        <button class="btn btn-primary">
                            📚 Requisitar este livro
                        </button>
                    </form>
                @else
                    <p class="text-sm text-red-600 mt-4">
                        🚫 Livro atualmente requisitado
                    </p>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary mt-4">
                    🔐 Iniciar sessão para requisitar
                </a>
            @endauth
                <div class="mt-6">
                    <a href="{{ route('livros.index') }}" class="btn btn-secondary">
                        ⬅ Voltar
                    </a>
                </div>
        </div>
        
    </div>



    {{-- TABS --}}
    <div role="tablist" class="tabs tabs-bordered tabs-lg">

        <input type="radio" name="tabs" role="tab"
               class="tab" aria-label="📝 Descrição" checked>
        <div role="tabpanel" class="p-4 bg-base-100 rounded-lg shadow">
            {{ $livro->bibliografia ?? 'Sem descrição disponível.' }}
        </div>

        <input type="radio" name="tabs" role="tab"
               class="tab" aria-label="⭐ Reviews">
        <div role="tabpanel" class="p-4 bg-base-100 rounded-lg shadow">
            <p class="text-gray-500 italic">
                Sem reviews disponíveis.
            </p>
        </div>

        <input type="radio" name="tabs" role="tab"
               class="tab" aria-label="🔁 Livros Relacionados">
        <div role="tabpanel" class="p-4 bg-base-100 rounded-lg shadow">

            @if($sugestoes->isEmpty())
                <p class="text-gray-500 italic">
                    Sem sugestões encontradas 😕
                </p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($sugestoes as $item)
                        @php
                            $info = $item['volumeInfo'] ?? [];
                        @endphp

                        <div class="card bg-white shadow-md">
                            @if(isset($info['imageLinks']['thumbnail']))
                                <figure class="px-4 pt-4">
                                    <img src="{{ $info['imageLinks']['thumbnail'] }}" class="rounded h-40 object-cover" />
                                </figure>
                            @endif

                            <div class="card-body">
                                <h3 class="font-semibold text-sm">
                                    {{ $info['title'] ?? 'Sem título' }}
                                </h3>

                                <p class="text-xs text-gray-600">
                                    {{ $info['authors'][0] ?? 'Autor desconhecido' }}
                                </p>

                                <form action="{{ route('admin.googlebooks.import') }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="book" value="{{ json_encode($item) }}">
                                    <button class="btn btn-success btn-sm mt-2 w-full">
                                        📥 Importar
                                    </button>
                                </form>
                            </div>
                        </div>

                    @endforeach
                </div>
            @endif
        </div>


        @if(auth()->check() && auth()->user()->isAdmin())
        <input type="radio" name="tabs" role="tab"
               class="tab" aria-label="📊 Histórico">
        <div role="tabpanel" class="p-4 bg-base-100 rounded-lg shadow">

            @if($historico->count() === 0)
                <p class="text-gray-500 italic">Este livro nunca foi requisitado.</p>
            @else
                <ul class="space-y-2">
                    @foreach($historico as $reg)
                        <li class="border-b pb-2">
                            <strong>{{ $reg->user->name }}</strong>
                            requisitou em {{ $reg->data_requisicao->format('d/m/Y') }}
                            @if($reg->data_entrega)
                                e entregou em {{ $reg->data_entrega->format('d/m/Y') }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif

        </div>
        @endif

    </div>

</div>

@endsection
