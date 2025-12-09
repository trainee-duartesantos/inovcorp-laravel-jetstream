@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-6">📍 Morada de Entrega</h2>

    <form action="{{ route('checkout.address.store') }}" method="POST">
        @csrf

        <div class="space-y-4">

            <input type="text" name="nome" placeholder="Nome Completo"
                   class="input input-bordered w-full" required>

            <input type="email" name="email" placeholder="Email"
                   class="input input-bordered w-full" required>

            <input type="text" name="morada" placeholder="Morada"
                   class="input input-bordered w-full" required>

            <input type="text" name="cidade" placeholder="Cidade"
                   class="input input-bordered w-full" required>

            <input type="text" name="codigo_postal" placeholder="Código Postal"
                   class="input input-bordered w-full" required>

            <input type="text" name="telefone" placeholder="Telefone (opcional)"
                   class="input input-bordered w-full">

        </div>

        <div class="mt-6 flex justify-between">
            <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                ⬅ Voltar ao Carrinho
            </a>

            <button type="submit" class="btn btn-primary">
                ➡ Continuar para Pagamento
            </button>
        </div>

    </form>

</div>

@endsection
