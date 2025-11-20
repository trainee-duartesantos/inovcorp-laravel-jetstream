@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">As minhas Requisições</h1>

            @if(session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Livro</th>
                            <th>Data Requisição</th>
                            <th>Data Prevista</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requisicoes as $req)
                            <tr>
                                <td>{{ $req->codigo ?? $req->id }}</td>
                                <td>{{ $req->livro->nome ?? '—' }}</td>
                                <td>{{ optional($req->data_requisicao)->format('d/m/Y') }}</td>
                                <td>{{ optional($req->data_prevista)->format('d/m/Y') }}</td>
                                <td>
                                    @if($req->estado === 'pending')
                                        <span class="badge badge-warning">Pendente</span>
                                    @elseif($req->estado === 'returned')
                                        <span class="badge badge-success">Devolvido</span>
                                    @else
                                        <span class="badge">{{ $req->estado }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-500">
                                    Ainda não tem requisições.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
