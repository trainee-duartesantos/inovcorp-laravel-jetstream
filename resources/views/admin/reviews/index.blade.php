@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4 flex justify-between">
        📊 Gestão de Avaliações
    </h1>
    <a href="{{ route('dashboard') }}" class="btn btn-ghost mt-4">
                ⬅ Voltar ao Dashboard
            </a>

    @if($reviews->count() === 0)
        <div class="alert alert-info text-center">
            Nenhuma avaliação pendente no momento.
        </div>
    @else
        <table class="table w-full bg-white shadow rounded-lg">
            <thead class="bg-gray-200">
            <tr>
                <th>Utilizador</th>
                <th>Livro</th>
                <th>Rating</th>
                <th>Comentário</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($reviews as $review)
            <tr>
                <td>{{ $review->user->name }}</td>
                <td>{{ $review->livro->nome }}</td>
                <td>{{ $review->rating }} ⭐</td>
                <td>{{ $review->comment ?? '-' }}</td>
                <td class="text-center">
                    <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="inline">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="1">
                        <button class="btn btn-sm btn-success">Aprovar</button>
                    </form>

                    <form method="POST" action="{{ route('admin.reviews.update', $review) }}" 
                        onsubmit="return confirm('Tem certeza que quer recusar esta avaliação?')">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="2">

                        <input type="text" name="justification" class="border rounded px-2 text-sm"
                            placeholder="Motivo (obrigatório)" required>

                        <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                            Recusar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
            

        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    @endif
</div>

@endsection
