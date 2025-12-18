@extends('layouts.app')

@push('styles')
    {{-- CSS específico do dashboard --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')

<div class="max-w-6xl mx-auto mt-6 space-y-10">

    {{-- 🔹 Header do Livro --}}
    <div class="bg-base-100 p-6 rounded-xl shadow-xl flex flex-col lg:flex-row gap-8">

        {{-- Capa --}}
        <img src="{{ $livro->capa_final }}"
            class="w-44 h-64 object-cover rounded-md shadow-md"
            alt="{{ $livro->nome }}">

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

            <!-- Carrinho -->
            @auth
                <form action="{{ route('cart.add', $livro) }}" method="POST" class="mt-2">
                    @csrf
                    <button class="btn btn-success">
                        🛒 Adicionar ao Carrinho
                    </button>
                </form>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="btn btn-success mt-2">
                    🛒 Adicionar ao Carrinho (login necessário)
                </a>
            @endguest

                <!-- Botão disponibilidade -->
                @if(!$livro->disponivel)
                    <form method="POST" action="{{ route('livros.alerta', $livro->id) }}">
                        @csrf
                        <button class="btn btn-warning mt-3">
                            Avisar-me quando estiver disponível
                        </button>
                    </form>
                @endif

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
        <a class="tab" onclick="openTab(event, 'tab-relacionados')">Relacionados 📚</a>
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


    {{-- ⭐ Secção de Reviews --}}
    <div class="mt-10 bg-white p-6 rounded-lg shadow-md">

        <h2 class="text-2xl font-bold mb-4">⭐ Avaliações dos Leitores</h2>

        @if($totalReviews > 0)
            <div class="mb-6">
                <p class="text-lg">
                    <strong>Média:</strong>
                    <span class="text-yellow-500">{{ $mediaRating }}★</span>
                    <span class="text-gray-600 text-sm">
                        ({{ $totalReviews }} avaliação{{ $totalReviews > 1 ? 'es' : '' }})
                    </span>
                </p>
            </div>

            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="p-4 border rounded-lg bg-gray-50">
                        <div class="flex justify-between">
                            <strong>{{ $review->user->name }}</strong>

                            <span class="text-yellow-500 font-semibold">
                                {{ $review->rating }}★
                            </span>
                        </div>
                        
                        <p class="text-sm text-gray-700 mt-2">
                            {{ $review->comment ?? 'Sem comentário' }}
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $review->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                @endforeach
            </div>

        @else
            <p class="text-gray-600">
                🤷 Este livro ainda não tem avaliações aprovadas.
            </p>
        @endif
    </div>

    {{-- ⭐ Formulário de Review (apenas se devolveu o livro) --}}
    @if($podeAvaliar)

        <div class="mt-10 bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4">Dê a sua avaliação</h3>

            <form action="{{ route('livros.review', $livro) }}" method="POST">
                @csrf
                <input type="hidden" name="requisicao_id"
                    value="{{ $livro->requisicoes->where('user_id', auth()->id())->where('estado','entregue')->first()->id }}">

                {{-- Rating --}}
                <label class="block font-bold mb-2">Classificação:</label>
                <select name="rating" required class="border rounded p-2 mb-4">
                    <option value="">Selecione</option>
                    <option value="1">1 ★</option>
                    <option value="2">2 ★★</option>
                    <option value="3">3 ★★★</option>
                    <option value="4">4 ★★★★</option>
                    <option value="5">5 ★★★★★</option>
                </select>

                {{-- Comentário --}}
                <label class="block font-bold mb-2">Comentário:</label>
                <textarea name="comment" rows="3"
                    class="border rounded p-2 w-full mb-4"
                    placeholder="O que achou do livro? (opcional)"></textarea>

                <button class="btn btn-warning mt-4 px-4 py-2 text-dark font-semibold rounded shadow hover:bg-yellow-600 transition">
                    ⭐ Submeter Avaliação
                </button>
            </form>
        </div>

    @elseif(!auth()->check())
        <p class="mt-10 text-gray-600">
            🔐 <a href="{{ route('login') }}" class="text-blue-600 underline">Faça login</a>
            para avaliar este livro.
        </p>
    @endif


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
                            <h3 class="font-semibold mb-1 text-sm">{{ $info['title'] ?? 'Título desconhecido' }}</h3>
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

    {{-- TAB: Livros Relacionados --}}
    <div id="tab-relacionados" class="tab-content hidden bg-base-100 p-6 rounded-xl shadow-lg">

        @if($relacionados->isEmpty())
            <p class="text-base-content/60">
                🤷 Não conseguimos encontrar livros relacionados com base na descrição.
            </p>
        @else
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">

                @foreach($relacionados as $item)
                    @php
                        $liv = $item['livro'];
                        $score = round($item['score'] * 100);
                    @endphp

                    <a href="{{ route('livros.show', $liv) }}" class="block">
                        <div class="card bg-base-200 shadow-md hover:shadow-xl transition h-full">

                            @php
                                $capa = $liv->capa_url
                                    ? asset('storage/' . $liv->capa_url)
                                    : 'https://via.placeholder.com/150';
                            @endphp

                            <figure class="px-4 pt-4">
                                <img src="{{ $capa }}" class="rounded-md h-40 object-cover mx-auto">
                            </figure>

                            <div class="card-body">
                                <h3 class="font-semibold text-sm">{{ $liv->nome }}</h3>
                                <p class="text-xs text-base-content/60">
                                    Semelhança: {{ $score }}%
                                </p>
                            </div>

                        </div>
                    </a>
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
    <div >
        <footer class="footer sm:footer-horizontal footer-center">
            <aside>
                <p>Copyright © All right reserved by Inovcorp Group</p>
            </aside>
        </footer>
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
