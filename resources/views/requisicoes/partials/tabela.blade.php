@if ($lista->isEmpty())
    <p class="text-gray-600">Nenhuma requisição nesta categoria.</p>
@else
<table class="table w-full bg-white shadow rounded-lg">
    <thead class="bg-gray-800 text-white">
        <tr>
            <th>#</th>
            <th>Livro</th>
            <th>Data Requisição</th>
            <th>Data Prevista/Entrega</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($lista as $req)
        <tr>
            <td>{{ $req->numero }}</td>
            <td>{{ $req->livro->nome }}</td>
            <td>{{ \Carbon\Carbon::parse($req->data_requisicao)->format('d/m/Y') }}</td>
            <td>
                @if($req->data_entrega)
                    ✔ Entregue em {{ \Carbon\Carbon::parse($req->data_entrega)->format('d/m/Y') }}
                @else
                    {{ \Carbon\Carbon::parse($req->data_prevista)->format('d/m/Y') }}
                @endif
            </td>

            <td>
                @if ($req->estado === 'ativa')
                    <form action="{{ route('requisicoes.devolver', $req) }}" method="POST">
                        @csrf
                        <button
                            class="btn btn-sm btn-success"
                            onclick="return confirm('Confirmar devolução do livro?')"
                        >
                            Devolver Livro
                        </button>
                    </form>
                @else
                    —
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
