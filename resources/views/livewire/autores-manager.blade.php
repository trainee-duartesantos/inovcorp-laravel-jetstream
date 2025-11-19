<div class="p-6 bg-base-200 min-h-screen">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">✍️ Gestão de Autores</h1>

        <button wire:click="openModal" class="btn btn-primary">
            ➕ Adicionar Autor
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
                    <th>Foto</th>
                    <th>Nome</th>
                    <th class="w-40">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($autores as $autor)
                    <tr>
                        <td>
                            @php
                                $foto = $autor->foto
                                    ? asset('storage/'.$autor->foto)
                                    : asset('storage/images/placeholders/placeholder-author.jpg');
                            @endphp

                            <img src="{{ $foto }}"
                                 alt="Foto de {{ $autor->nome }}"
                                 class="w-12 h-12 rounded-full object-cover shadow">
                        </td>

                        <td class="font-semibold">
                            {{ $autor->nome }}
                        </td>

                        <td class="flex gap-2">
                            <button wire:click="edit({{ $autor->id }})"
                                    class="btn btn-xs btn-info">✏ Editar</button>

                            <button wire:click="confirmDelete({{ $autor->id }})"
                                    class="btn btn-xs btn-error">🗑 Apagar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-base-content/70">
                            Nenhum autor registado.
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
                    {{ $autor_id ? 'Editar Autor' : 'Novo Autor' }}
                </h2>

                <form wire:submit.prevent="store" class="space-y-4">

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Nome</span>
                        </label>
                        <input type="text"
                               wire:model="nome"
                               class="input input-bordered w-full"
                               placeholder="Nome do autor">
                        @error('nome') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="label">
                            <span class="label-text font-semibold">Foto</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="file" wire:model="foto" class="file-input file-input-bordered">

                            @if($foto_atual)
                                <img src="{{ asset('storage/'.$foto_atual) }}"
                                     class="h-14 w-14 rounded-full object-cover shadow">
                            @endif
                        </div>
                        @error('foto') <span class="text-error text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-action">
                        <button type="button" class="btn" wire:click="closeModal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            {{ $autor_id ? 'Guardar alterações' : 'Criar Autor' }}
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
                <p>Deseja mesmo eliminar este autor?</p>

                <div class="modal-action">
                    <button class="btn" wire:click="$set('modalDeleteOpen', false)">Cancelar</button>
                    <button class="btn btn-error" wire:click="delete">Apagar</button>
                </div>
            </div>
        </div>
    @endif

</div>
