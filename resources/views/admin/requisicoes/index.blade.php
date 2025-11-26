@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-base-200 min-h-screen">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold flex items-center gap-2">
                🔄 Gestão de Requisições
            </h1>
        </div>

        {{-- ALERTAS --}}
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

        {{-- INDICADORES --}}
        <div class="stats stats-horizontal shadow mb-6 w-full flex justify-between gap-6">
            <div class="stat">
                <div class="stat-title">Requisições Ativas:</div>
                <div class="stat-value text-warning">{{ $ativas }}</div>
            </div>

            <div class="stat">
                <div class="stat-title">Atrasadas:</div>
                <div class="stat-value text-error">{{ $atrasadas }}</div>
            </div>

            <div class="stat">
                <div class="stat-title">Últimos 30 dias:</div>
                <div class="stat-value text-info">{{ $ultimos30 }}</div>
            </div>

            <div class="stat">
                <div class="stat-title">Entregues Hoje:</div>
                <div class="stat-value text-success">{{ $entreguesHoje }}</div>
            </div>

        </div>


    {{-- FILTROS (URL ?filtro=...) --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.requisicoes.index', ['filtro' => 'todas']) }}"
           class="btn btn-sm {{ $filtro === 'todas' ? 'btn-primary' : 'btn-ghost' }}">
            Todas
        </a>

        <a href="{{ route('admin.requisicoes.index', ['filtro' => 'ativas']) }}"
           class="btn btn-sm {{ $filtro === 'ativas' ? 'btn-primary' : 'btn-ghost' }}">
            Ativas
        </a>

        <a href="{{ route('admin.requisicoes.index', ['filtro' => 'atrasadas']) }}"
           class="btn btn-sm {{ $filtro === 'atrasadas' ? 'btn-primary' : 'btn-ghost' }}">
            Atrasadas
        </a>

        <a href="{{ route('admin.requisicoes.index', ['filtro' => 'entregues']) }}"
           class="btn btn-sm {{ $filtro === 'entregues' ? 'btn-primary' : 'btn-ghost' }}">
            Entregues
        </a>

        <a href="{{ route('admin.requisicoes.index', ['filtro' => '30dias']) }}"
           class="btn btn-sm {{ $filtro === '30dias' ? 'btn-primary' : 'btn-ghost' }}">
            Últimos 30 dias
        </a>
    </div>

    {{-- TABELA --}}
    <div class="overflow-x-auto bg-base-100 shadow rounded-lg">
        <table class="table w-full">
            <thead class="bg-base-300 text-base-content">
                <tr>
                    <th>#</th>
                    <th>Cidadão</th>
                    <th>Livro</th>
                    <th>Data Requisição</th>
                    <th>Data Prevista</th>
                    <th>Dias decorridos</th>  
                    <th>Estado</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requisicoes as $requisicao)
                    <tr>
                        <td>{{ $requisicao->numero ?? $requisicao->id }}</td>
                        <td>{{ $requisicao->user->name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('admin.requisicoes.show', $requisicao->id) }}"
                               class="text-blue-600 hover:underline">
                                {{ $requisicao->livro->nome ?? '—' }}
                            </a>
                        </td>
                        <td>{{ optional($requisicao->data_requisicao)->format('d/m/Y') }}</td>
                        <td>{{ optional($requisicao->data_prevista)->format('d/m/Y') }}</td>

                        {{-- 👇 Dias decorridos (usa accessor do model) --}}
                        <td>
                            @if(!is_null($requisicao->dias_decorridos))
                                <span class="badge badge-ghost">
                                    {{ $requisicao->dias_decorridos }} dias
                                </span>
                            @else
                                —
                            @endif
                        </td>

                        {{-- Estado com badge daisyUI --}}
                        <td>
                            <span class="badge {{ $requisicao->estado_badge }}">
                                {{ $requisicao->estado_formatado }}
                            </span>
                        </td>

                        <td>
                            @if(in_array($requisicao->estado, ['ativa', 'atrasada']))
                                <form action="{{ route('admin.requisicoes.entregar', $requisicao->id) }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-xs btn-success">
                                        ✅ Confirmar Entrega
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-base-content/60">
                                    @if($requisicao->data_entrega)
                                        Entregue em {{ $requisicao->data_entrega->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-base-content/60">
                            Ainda não existem requisições.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
