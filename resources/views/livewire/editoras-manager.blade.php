
<div class="p-4 lg:p-6 bg-base-200 min-h-screen">

    @if(session()->has('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold flex items-center gap-2">
            🏢 Gestão de Editoras
        </h1>

        <button wire:click="openModal" class="btn btn-primary">
            ➕ Adicionar Editora
        </button>
    </div>

    {{-- Pesquisa --}}
    <div class="mb-4 max-w-md">
        <input wire:model.debounce.300ms="search"
               type="text"
               placeholder="Pesquisar por nome..."
               class="input input-bordered w-full" />
    </div>

    <div class="overflow-x-auto bg-base-100 shadow rounded-lg">
        <table class="table w-full">
            <thead class="bg-base-300 text-base-content">
                <tr>
                    <th>Logótipo</th>
                    <th>Nome</th>
                    <th class="w-40">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($editoras as $editora)
                    <tr>
                        <td>
                            @if($editora->logo_url)
                                <img src="{{ asset('storage/'.$editora->logo_url) }}"
                                     class="w-12 h-12 object-contain bg-white rounded shadow">
                            @else
                                <span class="badge">Sem logótipo</span>
                            @endif
                        </td>
                        <td class="font-semibold">{{ $editora->nome }}</td>
                        <td>
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $editora->id }})"
                                        class="btn btn-xs btn-info">
                                    ✏ Editar
                                </button>
                                <button wire:click="confirmDelete({{ $editora->id }})"
                                        class="btn btn-xs btn-error">
                                    🗑 Apagar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-sm text-base-content/60">
                            Nenhuma editora encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $editoras->links() }}
    </div>

    {{-- Modal Criar/Editar --}}
    @if($modalOpen)
        <div class="modal modal-open">
            <div class="modal-box max-w-md">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $editora_id ? 'Editar Editora' : 'Nova Editora' }}
                </h2>

                <form wire:submit.prevent="store" class="space-y-3">

                    <div>
                        <label class="label"><span class="label-text">Nome</span></label>
                        <input type="text" wire:model="nome" class="input input-bordered w-full">
                        @error('nome') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label"><span class="label-text">Logótipo (opcional)</span></label>
                        <div class="flex items-center gap-3">
                            <input type="file" wire:model="logo"
                                   class="file-input file-input-bordered file-input-sm">
                            @if($logo_atual)
                                <img src="{{ asset('storage/'.$logo_atual) }}"
                                     class="h-10 w-10 rounded bg-white object-contain shadow">
                            @endif
                        </div>
                        @error('logo') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-action">
                        <button type="button" class="btn" wire:click="closeModal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            {{ $editora_id ? 'Guardar alterações' : 'Criar Editora' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

    {{-- Modal Apagar --}}
    @if($modalDeleteOpen)
        <div class="modal modal-open">
            <div class="modal-box max-w-md">
                <h2 class="text-lg font-bold text-red-600">⚠ Confirmar Eliminação</h2>
                <p>Deseja mesmo eliminar esta editora?</p>

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

