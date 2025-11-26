<div class="p-6 bg-base-200 min-h-screen">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">✍️ Gestão de Autores</h1>
        <button wire:click="openModal" class="btn btn-primary">➕ Adicionar Autor</button>
    </div>
    <div class="mb-4">
        <input type="text"
            wire:model.live="search"
            class="input input-bordered w-full"
            placeholder="Pesquisar autor...">
    </div>


    <div class="overflow-x-auto bg-base-100 shadow rounded-lg">
        <table class="table w-full">
            <thead class="bg-base-300 text-base-content">
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach($autores as $autor)
                    <tr>
                        <td>
                            @if($autor->foto)
                                <img src="{{ asset('storage/'.$autor->foto) }}"
                                    class="w-12 h-12 object-cover rounded-full shadow">
                            @else
                                <img src="{{ asset('storage/images/placeholders/placeholder-author.jpg') }}"
                                    class="w-12 h-12 rounded-full shadow">
                            @endif
                        </td>
                        <td class="font-semibold">
                            {{ $autor->nome }}
                        </td>
                        <td class="flex gap-2">
                            <button wire:click="edit({{ $autor->id }})" class="btn btn-xs btn-info">
                                ✏ Editar
                            </button>
                            <button wire:click="confirmDelete({{ $autor->id }})" class="btn btn-xs btn-error">
                                🗑 Apagar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    {{-- MODAL CRIAR/EDITAR --}}
    @if($modalOpen)
        <div class="modal modal-open">
            <div class="modal-box max-w-md space-y-3">
                <h2 class="text-xl font-semibold">
                    {{ $autor_id ? 'Editar Autor' : 'Novo Autor' }}
                </h2>

                <input type="text" wire:model="nome" class="input input-bordered w-full"
                       placeholder="Nome do Autor">
                @error('nome') <span class="text-error text-xs">{{ $message }}</span> @enderror

                <input type="file" wire:model="foto" class="file-input file-input-bordered w-full">

                @if($foto_atual)
                    <img src="{{ asset('storage/'.$foto_atual) }}" class="h-16 rounded shadow mt-2">
                @endif

                <div class="modal-action">
                    <button wire:click="closeModal" class="btn">Cancelar</button>
                    <button wire:click="store" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </div>
    @endif


    {{-- MODAL APAGAR --}}
    @if($modalDeleteOpen)
        <div class="modal modal-open">
            <div class="modal-box">
                <p>Tem a certeza que pretende apagar?</p>
                <div class="modal-action">
                    <button wire:click="$set('modalDeleteOpen', false)" class="btn">Cancelar</button>
                    <button wire:click="delete" class="btn btn-error">Apagar</button>
                </div>
            </div>
        </div>
    @endif

</div>
