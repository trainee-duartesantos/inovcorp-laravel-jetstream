@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">📦 Encomendas</h1>

<div class="overflow-x-auto bg-white shadow rounded-lg p-4">
    <table class="table w-full">
        <thead>
            <tr class="bg-base-200">
                <th>ID</th>
                <th>Utilizador</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Data</th>
                <th>Ação</th>
            </tr>
        </thead>

        <tbody>
            @foreach($encomendas as $e)
            <tr>
                <td>#{{ $e->id }}</td>
                <td>{{ $e->user->name }}</td>
                <td>{{ number_format($e->total, 2, ',', '.') }} €</td>

                <td>
                    <span class="badge 
                        @if($e->status === 'pago') badge-success
                        @elseif($e->status === 'pendente') badge-warning
                        @else badge-error @endif">
                        {{ ucfirst($e->status) }}
                    </span>
                </td>

                <td>{{ $e->created_at->format('d/m/Y H:i') }}</td>

                <td>
                    <a href="{{ route('admin.encomendas.show', $e->id) }}" class="btn btn-sm btn-outline">
                        Ver detalhes
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
