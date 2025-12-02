@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4 flex justify-between">
        📊 Gestão de Avaliações
    </h1>

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

                    <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="inline">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="2">
                        <input type="hidden" name="justification" value="Recusado pelo administrador">
                        <button class="btn btn-sm btn-error ms-1">Recusar</button>
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
