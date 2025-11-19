<div class="p-6 bg-base-200 min-h-screen">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">🏢 Gestão de Editoras</h1>

        <button wire:click="openModal" class="btn btn-primary">
            ➕ Adicionar Editora
        </button>
    </div>

    {{-- ALERTA DE SUCESSO --}}
    @if (session('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

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
                            @php
                                // Se tiver logotipo no campo novo, usa-o.
                                // Caso contrário, tenta logo_url (do seeder antigo),
                                // e se não houver, usa placeholder.
                                $logo =
                                    $editora->logotipo
                                        ? asset('storage/'.$editora->logotipo)
                                        : ($editora->logo_url
                                            ? asset($editora->logo_url)
                                            : asset('storage/images/placeholders/placeholder-publisher.svg'));
                            @endphp

                            <img src="{{ $logo }}"
                                 alt="Logótipo de {{ $editora->nome }}"
                                 class="w-16 h-16 object-contain rounded bg-white p-1 shadow">
                        </td>

                        <td class="font-semibold">
                            {{ $editora->nome }}
                        </td>

                        <td class="flex gap-2">
                            <button wire:click="edit({{ $editora->id }})"
                                    class="btn btn-xs btn-info">✏ Editar</button>

                            <button wire:click="confirmDelete({{ $editora->id }})"
                                    class="btn btn-xs btn-error">🗑 Apagar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-base-content/70">
                            Nenhuma editora registada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL CRIAR / EDITAR --}}
    @if($modalOpen)
        <div class="modal modal-open">
            <div class="modal-box max-w-md">
                <h2 class="text-xl font-semibold mb-3">
                    {{ $editora_id ? 'Editar Editora' : 'Nova Editora' }}
                </h2>

                <form wire:submit.prevent="store" class="space-y-4">

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Nome</span>
                        </label>
                        <input type="text"
                               wire:model="nome"
                               class="input input-bordered w-full"
                               placeholder="Nome da editora">
                        @error('nome') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Logótipo</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="file" wire:model="logotipo" class="file-input file-input-bordered">

                            @if($logotipo_atual)
                                <img src="{{ asset('storage/'.$logotipo_atual) }}"
                                     class="h-14 w-14 rounded object-contain bg-white p-1 shadow">
                            @endif
                        </div>
                        @error('logotipo') <span class="text-error text-xs">{{ $message }}</span> @enderror
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

    {{-- MODAL APAGAR --}}
    @if($modalDeleteOpen)
        <div class="modal modal-open">
            <div class="modal-box">
                <h2 class="text-lg font-bold text-red-600">⚠ Confirmar Eliminação</h2>
                <p>Deseja mesmo eliminar esta editora?</p>

                <div class="modal-action">
                    <button class="btn" wire:click="$set('modalDeleteOpen', false)">Cancelar</button>
                    <button class="btn btn-error" wire:click="delete">Apagar</button>
                </div>
            </div>
        </div>
    @endif

</div>
