@component('mail::message')
# Requisição Criada

Olá {{ $requisicao->user->name }},

A sua requisição do livro **{{ $requisicao->livro->titulo }}** foi registada com sucesso.

### Detalhes:
- Número: {{ $requisicao->numero }}
- Data da requisição: {{ $requisicao->data_requisicao->format('d/m/Y') }}
- Data prevista de entrega: {{ $requisicao->data_prevista->format('d/m/Y') }}

@component('mail::button', ['url' => url('/requisicoes/'.$requisicao->id)])
Ver Requisição
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
