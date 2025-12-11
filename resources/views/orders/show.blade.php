@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <h1 class="text-2xl font-bold mb-6">
        📦 Detalhes da Encomenda #{{ $order->id }}
    </h1>

    {{-- Estado --}}
    <p class="mb-2">
        Estado:
        <span class="
            px-2 py-1 rounded text-white text-sm
            @if($order->status === 'pago') bg-green-600
            @elseif($order->status === 'pendente') bg-yellow-500
            @else bg-red-600 @endif
        ">
            {{ ucfirst($order->status) }}
        </span>
    </p>

    {{-- Dados da entrega --}}
    <div class="mt-4 mb-6">
        <h2 class="text-lg font-semibold">📍 Morada de Entrega</h2>
        <p><strong>{{ $order->nome }}</strong></p>
        <p>{{ $order->morada }}</p>
        <p>{{ $order->codigo_postal }} - {{ $order->cidade }}</p>
        <p>📞 {{ $order->telefone ?? '—' }}</p>
        <p class="text-gray-600 text-sm mt-2">
            Efetuada em {{ $order->created_at->format('d/m/Y H:i') }}
        </p>
    </div>

    {{-- Lista dos itens --}}
    <h2 class="text-lg font-semibold mb-3">📚 Livros comprados</h2>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 text-left">Livro</th>
                <th class="p-2 text-center">Qtd</th>
                <th class="p-2 text-right">Preço</th>
                <th class="p-2 text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr class="border-b">
                <td class="p-2">{{ $item->livro->nome }}</td>
                <td class="p-2 text-center">{{ $item->quantity }}</td>
                <td class="p-2 text-right">{{ number_format($item->preco_unitario, 2, ',', '.') }} €</td>
                <td class="p-2 text-right">{{ number_format($item->subtotal, 2, ',', '.') }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Total --}}
    <div class="text-right mt-6">
        <p class="text-xl font-bold">
            Total: <span class="text-green-600">
                {{ number_format($order->total, 2, ',', '.') }} €
            </span>
        </p>
    </div>

    {{-- Voltar --}}
    <a href="{{ route('user.orders') }}"
       class="mt-6 inline-block px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
        ⬅ Voltar ao histórico
    </a>

</div>
@endsection
