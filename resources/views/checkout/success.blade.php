@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow text-center">

    <h1 class="text-3xl font-bold text-green-600 mb-4">
        Pagamento confirmado!
    </h1>

    <p class="text-lg mb-6">
        Obrigado pela sua encomenda.<br>
        Nº Pedido: <strong>#{{ $order->id }}</strong>
    </p>

    <a href="{{ route('dashboard') }}"
       class="px-4 py-2 bg-blue-600 text-black rounded hover:bg-blue-700">
       📚 Voltar à Biblioteca
    </a>

</div>
@endsection
