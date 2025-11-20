@extends('layouts.admin')

@section('content')
<div class="card bg-white p-6 shadow-md">
    
    <h2 class="text-xl font-bold mb-4">Detalhes da Requisição</h2>
    
    <p><strong>Número:</strong> {{ $requisicao->numero }}</p>
    <p><strong>Livro:</strong> {{ $requisicao->livro->nome }}</p>
    <p><strong>Cidadão:</strong> {{ $requisicao->user->name }}</p>
    <p><strong>Data Requisição:</strong> {{ $requisicao->data_requisicao->format('d/m/Y') }}</p>
    <p><strong>Data Prevista:</strong> {{ $requisicao->data_prevista->format('d/m/Y') }}</p>
    <p><strong>Estado:</strong> 
        <span class="badge {{ $requisicao->estado == 'ativa' ? 'badge-warning' : 'badge-success' }}">
            {{ $requisicao->estado }}
        </span>
    </p>

    @if($requisicao->estado === 'ativa')
    <button class="btn btn-success mt-4" onclick="document.getElementById('modalEntrega').showModal();">
        Confirmar Entrega
    </button>
    @endif
</div>


{{-- Modal Entregar --}}
<dialog id="modalEntrega" class="modal">
    <form method="POST" action="{{ route('admin.requisicoes.entregar', $requisicao->id) }}" class="modal-box">
        @csrf
        <h3 class="font-bold text-lg">Confirmar entrega?</h3>
        <p class="py-4">Tem a certeza que pretende marcar este livro como entregue?</p>
        
        <div class="modal-action">
            <button type="button" onclick="document.getElementById('modalEntrega').close();" class="btn btn-error">Cancelar</button>
            <button type="submit" class="btn btn-primary">Confirmar</button>
        </div>
    </form>
</dialog>

@endsection
