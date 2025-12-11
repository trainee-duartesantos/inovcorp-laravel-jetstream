@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded shadow">

    <h1 class="text-2xl font-bold mb-6">📦 As Minhas Encomendas</h1>

    @if($orders->isEmpty())
        <p class="text-gray-600">Ainda não efetuou nenhuma encomenda.</p>
    @else
        <div class="space-y-4">

            @foreach($orders as $order)
                <div class="border p-4 rounded hover:bg-gray-50">

                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="font-bold text-lg">
                                Encomenda #{{ $order->id }}
                            </h2>

                            <p class="text-gray-600 text-sm">
                                Data: {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>

                            <p class="mt-1">
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

                            <p class="mt-1">
                                Total:
                                <strong>{{ number_format($order->total, 2, ',', '.') }} €</strong>
                            </p>
                        </div>

                        <a href="{{ route('user.orders.show', $order->id) }}"
                           class="btn btn-outline btn-sm">
                            Ver detalhes →
                        </a>
                    </div>

                </div>
            @endforeach

        </div>
    @endif

    <a href="{{ route('dashboard') }}"
       class="mt-6 inline-block px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">
        ⬅ Voltar ao Dashboard
    </a>

</div>
@endsection
