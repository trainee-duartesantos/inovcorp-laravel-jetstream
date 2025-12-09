@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white shadow-lg p-6 rounded-xl">

    <h1 class="text-3xl font-bold mb-6">🛒 O meu Carrinho</h1>

    @if($items->isEmpty())
        <p class="text-gray-500">O carrinho está vazio.</p>
        <a href="{{ route('livros.index') }}" class="btn btn-primary mt-4">⬅ Voltar ao catálogo</a>
    @else
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Livro</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->livro->nome }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->livro->preco, 2, ',', ' ') }}€</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="#" class="btn btn-success mt-6">
            ⚡ Prosseguir para Checkout
        </a>
    @endif

</div>
@endsection
