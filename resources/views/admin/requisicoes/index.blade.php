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
           class="btn btn-sm {{ $filtro === 'todas' ? 'btn-primary text-white' : 'btn-ghost' }}">
            Todas
        </a>

        <a href="{{ route('admin.requisicoes.index', ['filtro' => 'ativas']) }}"
           class="btn btn-sm {{ $filtro === 'ativas' ? 'btn-primary text-white' : 'btn-ghost' }}">
            Ativas
        </a>

        <a href="{{ route('admin.requisicoes.index', ['filtro' => 'atrasadas']) }}"
           class="btn btn-sm {{ $filtro === 'atrasadas' ? 'btn-primary text-white' : 'btn-ghost' }}">
            Atrasadas
        </a>

        <a href="{{ route('admin.requisicoes.index', ['filtro' => 'entregues']) }}"
           class="btn btn-sm {{ $filtro === 'entregues' ? 'btn-primary text-white' : 'btn-ghost' }}">
            Entregues
        </a>

        <a href="{{ route('admin.requisicoes.index', ['filtro' => '30dias']) }}"
           class="btn btn-sm {{ $filtro === '30dias' ? 'btn-primary text-white' : 'btn-ghost' }}">
            Últimos 30 dias
        </a>
    </div>

    {{-- Tabela Desktop --}}
    <div class="overflow-x-auto bg-base-100 shadow rounded-lg hidden md:block">
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
                @foreach($requisicoes as $requisicao)
                <tr>
                    <td>{{ $requisicao->numero ?? $requisicao->id }}</td>
                    <td>{{ $requisicao->user->name }}</td>
                    <td>{{ $requisicao->livro->nome }}</td>
                    <td>{{ $requisicao->data_requisicao->format('d/m/Y') }}</td>
                    <td>{{ $requisicao->data_prevista->format('d/m/Y') }}</td>

                    <td>
                        <span class="badge badge-ghost">
                            {{ (int) $requisicao->dias_decorridos }} dias
                        </span>
                    </td>

                    <td>
                        <span class="badge {{ $requisicao->estado_badge }}">
                            {{ $requisicao->estado_formatado }}
                        </span>
                    </td>

                    <td>
                        @if(in_array($requisicao->estado, ['ativa','atrasada']))
                            <form action="{{ route('admin.requisicoes.entregar', $requisicao->id) }}"
                                method="POST">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-success">
                                    ✔ Confirmar Entrega
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-base-content/60">
                            Entregue em {{ $requisicao->data_entrega?->format('d/m/Y') }}
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Cards Mobile --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @foreach($requisicoes as $requisicao)
        <div class="card bg-base-100 shadow">
            <div class="card-body space-y-2">

                <h3 class="font-bold text-lg">{{ $requisicao->livro->nome }}</h3>

                <p class="text-sm"><strong>📌 Cidadão:</strong> {{ $requisicao->user->name }}</p>

                <p class="text-sm">
                    <strong>📅 Requisição:</strong> {{ $requisicao->data_requisicao->format('d/m/Y') }}
                </p>
                <p class="text-sm">
                    <strong>📆 Prevista:</strong> {{ $requisicao->data_prevista->format('d/m/Y') }}
                </p>

                <p class="text-sm">
                    <strong>📈 Dias:</strong>
                    {{ (int) $requisicao->dias_decorridos }} dias
                </p>

                <span class="badge {{ $requisicao->estado_badge }}">
                    {{ $requisicao->estado_formatado }}
                </span>

                <div class="pt-3">
                    @if(in_array($requisicao->estado,['ativa','atrasada']))
                        <form action="{{ route('admin.requisicoes.entregar', $requisicao->id) }}"
                            method="POST">
                            @csrf
                            <button class="btn btn-success btn-sm w-full">
                                ✔ Confirmar Entrega
                            </button>
                        </form>
                    @else
                        <span class="text-xs text-base-content/60">
                            Entregue em {{ $requisicao->data_entrega?->format('d/m/Y') }}
                        </span>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>

@endsection
