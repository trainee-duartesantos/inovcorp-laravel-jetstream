@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-md mt-10">

    <h1 class="text-3xl font-bold mb-6">📍 Morada de Entrega</h1>

    <p class="mb-6 text-gray-600">
        Insira os dados da morada onde os livros devem ser entregues.
    </p>

    <form action="#" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">📌 Rua / Avenida</label>
            <input type="text" name="rua" required class="border rounded p-2 w-full">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">🏠 Nº Porta</label>
            <input type="text" name="porta" required class="border rounded p-2 w-full">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">📮 Código Postal</label>
            <input type="text" name="postal" required class="border rounded p-2 w-full">
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">📍 Cidade</label>
            <input type="text" name="cidade" required class="border rounded p-2 w-full">
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                ⬅ Voltar ao Carrinho
            </a>

            {{-- Próxima fase será /checkout/pagamento --}}
            <button class="btn btn-success">
                Continuar para Pagamento 💳
            </button>
        </div>
    </form>

</div>

@endsection
