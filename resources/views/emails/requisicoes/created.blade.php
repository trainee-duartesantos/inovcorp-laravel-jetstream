@component('mail::message')
# 📚 Requisição Criada com Sucesso!

Olá {{ $requisicao->user->name }},

A sua requisição foi registada com os seguintes detalhes:

**Número:** {{ $requisicao->numero }}  
**Livro:** {{ $requisicao->livro->nome }}  
**Data de Requisição:** {{ $requisicao->data_requisicao->format('d/m/Y') }}  
**Data Prevista de Entrega:** {{ $requisicao->data_prevista->format('d/m/Y') }}

@component('mail::panel')
@if($requisicao->livro->capa_url)
<img src="{{ asset('storage/'.$requisicao->livro->capa_url) }}" width="150px">
@endif
@endcomponent

Agradecemos a sua utilização do serviço de Biblioteca da Inovcorp!

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
