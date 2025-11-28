<div class="p-6 bg-base-200 min-h-screen">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">🏢 Gestão de Editoras</h1>
        <button wire:click="openModal" class="btn btn-primary">➕ Adicionar Editora</button>
    </div>
    <div class="mb-4">
        <input type="text"
            wire:model.live="search"
            class="input input-bordered w-full"
            placeholder="Pesquisar editora...">
    </div>


    {{-- Tabela — Desktop apenas --}}
    <div class="overflow-x-auto bg-base-100 shadow rounded-lg hidden md:block">
        <table class="table w-full">
            <thead class="bg-base-300 text-base-content">
                <tr>
                    <th>Logo</th>
                    <th>Nome</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($editoras as $editora)
                <tr>
                    <td class="p-2">
                        <img src="{{ $editora->logotipo ? asset('storage/images/editoras'.$editora->logotipo) : asset('storage/images/placeholders/publisher.png') }}"
                            class="w-12 h-12 object-contain rounded">
                    </td>
                    <td class="font-medium">{{ $editora->nome }}</td>
                    <td class="text-right">
                        <button wire:click="edit({{ $editora->id }})" class="btn btn-sm btn-primary">✏ Editar</button>
                        <button wire:click="confirmDelete({{ $editora->id }})" class="btn btn-sm btn-error">🗑 Apagar</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Cards — Mobile --}}
    <div class="grid grid-cols-1 gap-4 md:hidden">
        @foreach($editoras as $editora)
            <div class="card bg-base-100 shadow-md p-4 flex items-center gap-4">

                {{-- Logo --}}
                <img src="{{ $editora->logotipo ? asset('storage/'.$editora->logotipo) : asset('/storage/images/placeholders/placeholder-publisher.svg') }}"
                    class="w-16 h-16 object-contain rounded">

                {{-- Info --}}
                <div class="flex-1">
                    <h3 class="font-bold text-lg">{{ $editora->nome }}</h3>
                </div>

                {{-- Ações --}}
                <div class="flex flex-col gap-1">
                    <button wire:click="edit({{ $editora->id }})" class="btn btn-xs btn-primary">✏ Editar / Alterar</button>
                    <button wire:click="confirmDelete({{ $editora->id }})" class="btn btn-xs btn-error">🗑 Apagar</button>
                </div>

            </div>
        @endforeach
    </div>



    {{-- Modal Criar/Editar --}}
    @if($modalOpen)
        <div class="modal modal-open">
            <div class="modal-box max-w-md space-y-3">
                <h2 class="text-xl font-semibold">
                    {{ $editora_id ? 'Editar Editora' : 'Nova Editora' }}
                </h2>

                <input type="text" wire:model="nome" class="input input-bordered w-full"
                       placeholder="Nome da Editora">

                <input type="file" wire:model="logotipo" class="file-input file-input-bordered w-full">

                @if($logotipo_atual)
                    <img src="{{ asset('storage/'.$logotipo_atual) }}" class="h-16 rounded shadow mt-2">
                @endif

                <div class="modal-action">
                    <button wire:click="closeModal" class="btn">Cancelar</button>
                    <button wire:click="store" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </div>
    @endif


    {{-- Modal Apagar --}}
    @if($modalDeleteOpen)
        <div class="modal modal-open">
            <div class="modal-box">
                <p>Tem certeza que deseja eliminar?</p>
                <div class="modal-action">
                    <button wire:click="$set('modalDeleteOpen', false)" class="btn">Cancelar</button>
                    <button wire:click="delete" class="btn btn-error">Eliminar</button>
                </div>
            </div>
        </div>
    @endif

</div>
