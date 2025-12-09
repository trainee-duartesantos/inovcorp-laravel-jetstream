@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto mt-10 space-y-8">

    <h1 class="text-3xl font-bold mb-6">🛒 O meu Carrinho</h1>

    {{-- Se o carrinho estiver vazio --}}
    @if($cart->items->isEmpty())
        <div class="bg-base-200 p-6 rounded-lg text-center">
            <p class="text-lg text-base-content/70">O seu carrinho está vazio 😕</p>
            <a href="{{ route('livros.index') }}" class="btn btn-secondary mt-4">
                ⬅ Voltar ao catálogo
            </a>
        </div>

    @else

        {{-- LISTA DE ITENS --}}
        <div class="bg-base-100 shadow-lg rounded-xl p-6 space-y-6">

            @foreach($cart->items as $item)
                <div class="flex items-center justify-between border-b pb-4">

                    {{-- Info Livro --}}
                    <div class="flex items-center gap-4">

                        {{-- Capa --}}
                        <img src="{{ asset('storage/'.$item->livro->capa_url) }}"
                             class="w-20 h-28 object-cover rounded shadow">

                        {{-- Info --}}
                        <div>
                            <h3 class="font-bold text-lg">{{ $item->livro->nome }}</h3>

                            <p class="text-sm text-base-content/60">
                                {{ $item->livro->autores->pluck('nome')->join(', ') }}
                            </p>

                            <p class="text-sm mt-1 font-semibold text-green-600">
                                {{ number_format($item->livro->preco,2,',','.') }} €
                            </p>
                        </div>
                    </div>

                    {{-- Remover Item --}}
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-error btn-sm">
                            ❌ Remover
                        </button>
                    </form>
                </div>
            @endforeach

        </div>

        {{-- TOTAL + CHECKOUT --}}
        <div class="flex justify-between items-center mt-6">

            <h2 class="text-2xl font-bold">
                Total:
                <span class="text-green-600">
                    {{ number_format($total,2,',','.') }} €
                </span>
            </h2>

            <div class="flex gap-4">
                <a href="{{ route('livros.index') }}" class="btn btn-outline-primary btn-lg">
                    ➕ Adicionar mais livros
                </a>

                <a href="{{ route('checkout.address') }}" class="btn btn-success btn-lg">
                    ✔ Avançar para Checkout
                </a>
            </div>
        </div>

    @endif

</div>

@endsection
