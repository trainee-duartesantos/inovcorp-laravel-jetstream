@extends('layouts.admin')

@section('content')
<div class="p-6 bg-base-200 min-h-screen">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold flex items-center gap-2">
            🔄 Gestão de Requisições
        </h1>
    </div>

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

    <div class="overflow-x-auto bg-base-100 shadow rounded-lg">
        <table class="table w-full">
            <thead class="bg-base-300 text-base-content">
                <tr>
                    <th>#</th>
                    <th>Cidadão</th>
                    <th>Livro</th>
                    <th>Data Requisição</th>
                    <th>Data Prevista</th>
                    <th>Estado</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisicoes as $requisicao)
                    <tr>
                        <td>{{ $requisicao->codigo ?? $requisicao->id }}</td>
                        <td>{{ $requisicao->user->name ?? '—' }}</td>
                        <td>{{ $requisicao->livro->nome ?? '—' }}</td>
                        <td>{{ optional($requisicao->data_requisicao)->format('d/m/Y') }}</td>
                        <td>{{ optional($requisicao->data_prevista)->format('d/m/Y') }}</td>
                        <td>
                            @if($requisicao->estado === 'pending')
                                <span class="badge badge-warning">Pendente</span>
                            @elseif($requisicao->estado === 'returned')
                                <span class="badge badge-success">Devolvido</span>
                            @else
                                <span class="badge">{{ $requisicao->estado }}</span>
                            @endif
                        </td>
                        <td>
                            @if($requisicao->estado === 'pending')
                                <form action="{{ route('admin.requisicoes.entregar', $requisicao) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-xs btn-success">
                                        ✅ Confirmar Entrega
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-base-content/60">
                                    Entregue em {{ optional($requisicao->data_entrega)->format('d/m/Y') }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-base-content/60">
                            Ainda não existem requisições.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
