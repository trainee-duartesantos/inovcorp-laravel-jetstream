@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto mt-6 space-y-10">

    {{-- 🔹 Header do Livro --}}
    <div class="bg-base-100 p-6 rounded-xl shadow-xl flex flex-col lg:flex-row gap-8">

        {{-- Capa --}}
        <img src="{{ $livro->capa_url ? asset('storage/'.$livro->capa_url) : 'https://via.placeholder.com/150' }}"
             class="w-44 h-64 object-cover rounded-md shadow-md">

        {{-- Informações --}}
        <div class="flex-1 space-y-2">
            <h1 class="text-4xl font-bold">{{ $livro->nome }}</h1>

            <p class="text-base-content/70"><strong>Autor(es):</strong> {{ $livro->autores->pluck('nome')->join(', ') }}</p>
            <p class="text-base-content/70"><strong>Editora:</strong> {{ $livro->editora->nome }}</p>
            <p class="text-base-content/70"><strong>ISBN:</strong> {{ $livro->isbn }}</p>

            {{-- Badge Disponibilidade --}}
            <span class="badge badge-lg {{ $livro->disponivel ? 'badge-success' : 'badge-error' }}">
                {{ $livro->disponivel ? 'Disponível' : 'Requisitado' }}
            </span>

            {{-- Botão Requisição --}}
            @auth
                @if($livro->disponivel)
                    <form action="{{ route('requisicoes.store') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="livro_id" value="{{ $livro->id }}">
                        <button class="btn btn-primary md:w-auto">
                            📚 Requisitar este livro
                        </button>
                    </form>
                @else
                    <p class="text-sm text-error mt-4">🚫 Livro requisitado</p>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary mt-4 w-full md:w-auto">
                    🔐 Iniciar sessão
                </a>
            @endauth

            <a href="{{ route('livros.index') }}" class="btn btn-ghost mt-4">
                ⬅ Voltar ao catálogo
            </a>
        </div>

    </div>


    {{-- 🔹 Tabs --}}
    <div class="tabs tabs-boxed w-full justify-center">
        <a class="tab tab-active" onclick="openTab(event, 'tab-descricao')">Descrição</a>
        <a class="tab" onclick="openTab(event, 'tab-reviews')">Avaliações ⭐</a>
        <a class="tab" onclick="openTab(event, 'tab-sugestoes')">Sugestões 🔍</a>
        @if(auth()->check() && auth()->user()->isAdmin())
            <a class="tab" onclick="openTab(event, 'tab-historico')">Histórico 📜</a>
        @endif
    </div>


    {{-- TAB: Descrição --}}
    <div id="tab-descricao" class="tab-content active bg-base-100 p-6 rounded-xl shadow-lg">
        <p class="leading-7 text-base-content/80">
            {{ $livro->bibliografia ?? 'Sem descrição disponível.' }}
        </p>
    </div>


    {{-- TAB: Reviews --}}
    <div id="tab-reviews" class="tab-content hidden bg-base-100 p-6 rounded-xl shadow-lg space-y-6">
        
        {{-- Resumo --}}
        <div>
            <h2 class="text-2xl font-semibold mb-2">Avaliações dos leitores</h2>

            <p class="text-lg flex items-center gap-2 font-semibold">
                ⭐ {{ $mediaRating ?? 0 }} / 5 
                <span class="text-base-content/60 text-sm">({{ $totalReviews }} avaliações)</span>
            </p>
        </div>

        {{-- Form Avaliação --}}
        @auth
            <form action="{{ route('livros.review', $livro) }}" method="POST" class="space-y-3">
                @csrf
                <select name="rating" class="select select-bordered w-full" required>
                    <option value="">Escolha...</option>
                    @for($i=1; $i<=5; $i++)
                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                    @endfor
                </select>

                <textarea name="comment" class="textarea textarea-bordered w-full"
                    placeholder="Comentário (opcional)"></textarea>

                <button class="btn btn-primary w-full md:w-auto">💾 Guardar avaliação</button>
            </form>
        @endauth

        {{-- Lista de Reviews --}}
        @foreach($livro->reviews()->latest()->get() as $review)
            <div class="border-b pb-3">
                <p class="font-semibold">{{ $review->user->name }}</p>
                <p class="text-warning">{{ str_repeat('⭐', $review->rating) }}</p>
                @if($review->comment)
                    <p class="text-sm mt-1">{{ $review->comment }}</p>
                @endif
            </div>
        @endforeach

        @if($totalReviews == 0)
            <p class="text-base-content/50 italic">Ainda sem avaliações.</p>
        @endif
    </div>


    {{-- TAB: Sugestões --}}
    <div id="tab-sugestoes" class="tab-content hidden bg-base-100 p-6 rounded-xl shadow-lg">

        @if($sugestoes->isEmpty())
            <p class="text-base-content/50">Nenhuma sugestão encontrada.</p>
        @else
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($sugestoes as $item)
                    @php $info = $item['volumeInfo'] ?? []; @endphp

                    <div class="card bg-base-200 shadow-md hover:shadow-xl transition">
                        @if(isset($info['imageLinks']['thumbnail']))
                            <figure class="px-4 pt-4">
                                <img src="{{ $info['imageLinks']['thumbnail'] }}"
                                    class="rounded-md h-40 object-cover">
                            </figure>
                        @endif
                        <div class="card-body">
                            <h3 class="font-semibold mb-1 text-sm">{{ $info['title'] }}</h3>
                            <p class="text-xs text-base-content/70">{{ $info['authors'][0] ?? 'Autor desconhecido' }}</p>

                            @admin
                            <form action="{{ route('admin.googlebooks.import') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book" value="{{ json_encode($item) }}">
                                <button class="btn btn-success btn-sm mt-2 w-full">
                                    📥 Importar p/ BD
                                </button>
                            </form>
                            @endadmin
                        </div>
                    </div>

                @endforeach
            </div>
        @endif

    </div>


    {{-- TAB: Histórico (Admin Only) --}}
    @if(auth()->check() && auth()->user()->isAdmin())
        <div id="tab-historico" class="tab-content hidden bg-base-100 p-6 rounded-xl shadow-lg">
            @forelse($historico as $reg)
                <div class="border-b py-3">
                    <strong>{{ $reg->user->name }}</strong>
                    — requisitou em {{ $reg->data_requisicao->format('d/m/Y') }}
                    @if($reg->data_entrega)
                        <span class="text-green-600">
                            e entregou em {{ $reg->data_entrega->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
            @empty
                <p class="text-base-content/50 italic">Nenhum histórico disponível.</p>
            @endforelse
        </div>
    @endif

</div>


{{-- Script Tabs --}}
<script>
function openTab(evt, tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById(tabId).classList.remove('hidden');

    document.querySelectorAll('.tab').forEach(el => el.classList.remove('tab-active'));
    evt.target.classList.add('tab-active');
}
</script>

@endsection
