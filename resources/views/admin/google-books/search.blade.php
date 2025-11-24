@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-base-200 rounded-lg shadow-md">

    <h2 class="text-3xl font-bold mb-4">🔍 Importar Livros da Google Books</h2>

    {{-- Formulário de pesquisa --}}
    <form action="{{ route('admin.googlebooks.search') }}" method="GET" class="flex gap-3 mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Pesquisar título, ISBN, autor..."
               class="input input-bordered w-full">
        <button class="btn btn-primary">Pesquisar</button>
    </form>

    {{-- Resultados --}}
    @isset($results)
        @if(count($results) > 0)
            <table class="table w-full bg-base-100 shadow-md rounded-lg">
                <thead>
                    <tr class="bg-base-300">
                        <th>Capa</th>
                        <th>Título</th>
                        <th>Autor(es)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($results as $item)
                    @php
                        $volume = $item['volumeInfo'] ?? [];
                    @endphp
                    <tr>
                        <td>
                            @if(isset($volume['imageLinks']['thumbnail']))
                                <img src="{{ $volume['imageLinks']['thumbnail'] }}"
                                     class="w-16 rounded-md shadow">
                            @else
                                ❌
                            @endif
                        </td>
                        <td>{{ $volume['title'] ?? 'Sem título' }}</td>
                        <td>{{ $volume['authors'][0] ?? 'Autor desconhecido' }}</td>
                        <td>
                            {{-- Botão para importar --}}
                            <form action="{{ route('admin.googlebooks.import') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book" value="{{ json_encode($item) }}">
                                <button class="btn btn-success btn-sm">Importar 📥</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>Nenhum resultado encontrado 😕</p>
        @endif
    @endisset

</div>
@endsection
