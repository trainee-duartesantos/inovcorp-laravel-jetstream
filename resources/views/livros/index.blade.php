@extends('layouts.app')

@push('styles')
    {{-- CSS específico do dashboard --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')

    <div class="max-w-6xl mx-auto mt-6">
        <div class="max-w-6xl mx-auto mt-6">
            <h1 class="text-3xl font-bold mb-6 flex justify-between items-center">
                📚 Catálogo de Livros
            </h1>
        <div class="relative mb-6">
            <input type="text" id="google-search" class="input input-bordered w-full"
                placeholder="🔍 Pesquisar livros na Google Books...">
            <div id="google-results"
                class="absolute w-full mt-1 bg-white border rounded-lg shadow-lg z-50 hidden">
            </div>
            <script>
                let timeout = null;

                document.getElementById('google-search').addEventListener('keyup', function() {
                    clearTimeout(timeout);
                    let query = this.value.trim();

                    if (query.length < 3) {
                        document.getElementById('google-results').classList.add('hidden');
                        return;
                    }

                    timeout = setTimeout(() => fetchGoogleBooks(query), 400);
                });

                function fetchGoogleBooks(query) {
                    fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}&maxResults=5`)
                        .then(res => res.json())
                        .then(data => showResults(data.items || []));
                }

                function showResults(books) {
                    const container = document.getElementById('google-results');

                    if (!books.length) {
                        container.innerHTML = `<p class="p-3 text-gray-500">Nenhum resultado encontrado…</p>`;
                        container.classList.remove('hidden');
                        return;
                    }

                    container.innerHTML = books.map(b => {
                        const info = b.volumeInfo;
                        const img = info.imageLinks?.thumbnail || 'https://via.placeholder.com/50';
                        const autores = info.authors?.join(', ') || 'Autor desconhecido';

                        return `
                        <div class="p-3 border-b flex justify-between items-center hover:bg-gray-100">
                            <div class="flex items-center gap-3">
                                <img src="${img}" class="w-10 h-14 object-cover rounded shadow">
                                <div>
                                    <strong>${info.title}</strong><br>
                                    <span class="text-xs">${autores}</span>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.googlebooks.import') }}">
                                @csrf
                                <input type="hidden" name="book" value='${JSON.stringify(b)}'>
                                <button class="btn btn-xs btn-secondary">
                                    📥 Importar
                                </button>
                            </form>
                        </div>`;
                    }).join('');

                    container.classList.remove('hidden');
                }
            </script>

        </div>


        @include('components.alertas')

       <form method="GET" action="{{ route('livros.index') }}" class="w-full mb-4">
            <input type="text" name="q" value="{{ request('q') }}" 
                placeholder="Pesquisar por título, ISBN ou autor..."
                class="input input-bordered w-full">
        </form>

    </div>
    
    <table class="table w-full bg-white shadow rounded-lg">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th>Capa</th>
                <th>Nome</th>
                <th>Editora</th>
                <th>Estado</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($livros as $livro)
            <tr>
                <td class="p-2">
                    @php
                        $capa = $livro->capa_url
                            ? asset('storage/'.$livro->capa_url)
                            : asset('storage/images/placeholders/book-placehorlder.png');
                    @endphp

                    <img src="{{ $capa }}" class="w-12 h-16 object-cover rounded shadow-md">
                </td>

                <td>{{ $livro->nome }}</td>
                <td>{{ $livro->editora->nome }}</td>

                <td>
                    <span class="badge {{ $livro->disponivel ? 'badge-success' : 'badge-error' }}">
                        {{ $livro->disponivel ? 'Disponível' : 'Requisitado' }}
                    </span>
                </td>

                <td>
                    <a href="{{ route('livros.show', $livro) }}" class="btn btn-sm btn-secondary">
                        Ver detalhes
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    <div >
    <footer class="footer sm:footer-horizontal footer-center">
        <aside>
            <p>Copyright © All right reserved by Inovcorp Group</p>
        </aside>
    </footer>
</div>
@endsection
