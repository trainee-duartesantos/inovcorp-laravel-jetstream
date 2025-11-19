

<div class="p-6 bg-base-200 min-h-screen">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">📚 Gestão de Livros</h1>
        <button wire:click="openModal" class="btn btn-primary">➕ Adicionar Livro</button>
    </div>

    {{-- Pesquisa --}}
    <input wire:model.debounce.300ms="search" type="text" placeholder="Pesquisar por nome ou ISBN..."
           class="input input-bordered w-full mb-4" />

    {{-- Tabela --}}
    <div class="overflow-x-auto bg-base-100 shadow rounded-lg">
        <table class="table w-full">
            <thead class="bg-base-300 text-base-content">
                <tr>
                    <th>Foto</th>
                    <th>ISBN</th>
                    <th>Nome</th>
                    <th>Editora</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach($livros as $livro)
                    <tr>
                        <td>
                            @if($livro->capa_url)
                                <img src="{{ asset('storage/'.$livro->capa_url) }}"
                                     class="w-12 h-16 object-cover rounded shadow">
                            @else
                                <span class="badge">Sem capa</span>
                            @endif
                        </td>
                        <td>{{ $livro->isbn }}</td>
                        <td class="font-semibold">{{ $livro->nome }}</td>
                        <td>{{ $livro->editora->nome }}</td>
                        <td>{{ number_format($livro->preco, 2, ',', ' ') }} €</td>
                        <td class="flex gap-2">
                            <button wire:click="edit({{ $livro->id }})"
                                    class="btn btn-xs btn-info">✏ Editar</button>

                            <button wire:click="confirmDelete({{ $livro->id }})"
                                    class="btn btn-xs btn-error">🗑 Apagar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $livros->links() }}
    </div>

    {{-- Modal Criar/Editar --}}
    @if($modalOpen)
        <div class="modal modal-open">
            <div class="modal-box max-w-xl">
                <h2 class="text-xl font-semibold mb-3">
                    {{ $livro_id ? 'Editar Livro' : 'Novo Livro' }}
                </h2>

                <form wire:submit.prevent="store" class="space-y-3">

                    <input type="text" wire:model="isbn" class="input input-bordered w-full"
                           placeholder="ISBN">
                    @error('isbn') <span class="text-error text-xs">{{ $message }}</span> @enderror

                    <input type="text" wire:model="nome" class="input input-bordered w-full"
                           placeholder="Nome do livro">
                    @error('nome') <span class="text-error text-xs">{{ $message }}</span> @enderror

                    <select wire:model="editora_id" class="select select-bordered w-full">
                        <option value="">Selecione a editora</option>
                        @foreach($editoras as $editora)
                            <option value="{{ $editora->id }}">{{ $editora->nome }}</option>
                        @endforeach
                    </select>

                    <div>
                        <label class="font-semibold">Autores</label>
                        <select wire:model="autores_id"
                                class="select select-bordered w-full"
                                multiple size="4">
                            @foreach($autores as $autor)
                                <option value="{{ $autor->id }}">{{ $autor->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <textarea wire:model="bibliografia"
                              class="textarea textarea-bordered w-full"
                              placeholder="Bibliografia (opcional)"></textarea>

                    <input type="number" step="0.01" wire:model="preco"
                           class="input input-bordered w-full"
                           placeholder="Preço (€)">
                    @error('preco') <span class="text-error text-xs">{{ $message }}</span> @enderror

                    <div class="flex items-center gap-3">
                        <input type="file" wire:model="capa"
                               class="file-input file-input-bordered">
                        @if($capa_atual)
                            <img src="{{ asset('storage/'.$capa_atual) }}" class="h-14 rounded shadow">
                        @endif
                    </div>

                    <div class="modal-action">
                        <button type="button" class="btn" wire:click="closeModal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            {{ $livro_id ? 'Guardar alterações' : 'Criar Livro' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

    {{-- Modal Apagar --}}
    @if($modalDeleteOpen)
        <div class="modal modal-open">
            <div class="modal-box">
                <h2 class="text-lg font-bold text-red-600">⚠ Confirmar Eliminação</h2>
                <p>Deseja mesmo eliminar este livro?</p>

                <div class="modal-action">
                    <button class="btn" wire:click="$set('modalDeleteOpen', false)">
                        Cancelar
                    </button>
                    <button class="btn btn-error" wire:click="delete">
                        Apagar
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

