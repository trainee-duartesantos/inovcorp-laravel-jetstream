@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <h1 class="text-2xl font-bold mb-6">💳 Pagamento</h1>

    <p class="mb-4">Obrigado pela sua encomenda!</p>

    <p>
        Encomenda nº <strong>#{{ $order->id }}</strong><br>
        Valor total:
        <span class="text-green-600 font-bold">
            {{ number_format($order->total, 2, ',', '.') }} €
        </span>
    </p>

    <p class="mt-6 text-gray-600">
        👉 Clique abaixo para pagar com Stripe ⚡
    </p>

    <form action="{{ route('checkout.stripe', $order->id) }}" method="POST">
        @csrf
        <button class="mt-6 bg-blue-600 text-black px-4 py-2 rounded hover:bg-blue-700">
            💳 Pagar com Stripe
        </button>
    </form>


    <a href="{{ route('dashboard') }}"
       class="mt-6 ml-3 inline-block px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
        ⬅ Voltar ao Dashboard
    </a>

</div>
@endsection
