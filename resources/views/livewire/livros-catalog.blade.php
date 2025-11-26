<div class="max-w-6xl mx-auto mt-6">

    <h1 class="text-3xl font-bold mb-4">📚 Catálogo de Livros</h1>

    {{-- Campo de pesquisa listo! --}}
    <input type="text" wire:model.live.debounce.300ms="query"
           placeholder="Pesquisar livros por nome, ISBN ou autor..."
           class="input input-bordered w-full mb-6">

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
        @forelse($livros as $livro)
            <tr>
                <td class="p-2">
                    <img src="{{ $livro->capa_url ? asset('storage/'.$livro->capa_url) : 'https://via.placeholder.com/60' }}"
                        class="w-12 h-16 object-cover rounded">
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
        @empty
            <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">
                    Nenhum livro encontrado 👀
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <footer class="footer sm:footer-horizontal footer-center">
        <aside>
            <p>Copyright © All right reserved by Inovcorp Group</p>
        </aside>
    </footer>
</div>

