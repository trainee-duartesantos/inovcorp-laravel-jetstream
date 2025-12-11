@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">📄 Detalhes da Encomenda #{{ $order->id }}</h1>

@if(session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow-md rounded-lg p-6 mb-6">

    <h2 class="text-xl font-bold mb-4">👤 Cliente</h2>

    <p><strong>Nome:</strong> {{ $order->user->name }}</p>
    <p><strong>Email:</strong> {{ $order->user->email }}</p>

    <hr class="my-4">

    <h2 class="text-xl font-bold mb-4">📦 Itens da Encomenda</h2>

    <table class="table w-full">
        <thead>
            <tr class="bg-base-200">
                <th>Livro</th>
                <th>Preço</th>
                <th>Qtd.</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->livro->nome }}</td>
                    <td>{{ number_format($item->preco_unitario, 2, ',', '.') }} €</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->preco_unitario * $item->quantity, 2, ',', '.') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 text-right text-xl font-bold">
        Total: {{ number_format($order->total, 2, ',', '.') }} €
    </div>

</div>


{{-- ALTERAR ESTADO --}}
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">🔧 Alterar Estado da Encomenda</h2>

    <form method="POST" action="{{ route('admin.encomendas.updateStatus', $order->id) }}">
        @csrf

        <select name="status" class="select select-bordered w-full max-w-xs">
            <option value="pendente"   {{ $order->status === 'pendente' ? 'selected' : '' }}>Pendente</option>
            <option value="pago"       {{ $order->status === 'pago' ? 'selected' : '' }}>Pago</option>
            <option value="cancelado"  {{ $order->status === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>

        <button type="submit" class="btn btn-primary mt-3">
            Guardar
        </button>
    </form>
</div>

<a href="{{ route('admin.encomendas') }}" class="btn btn-outline mt-6">⬅ Voltar às Encomendas</a>

@endsection
