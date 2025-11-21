@component('mail::message')
# 📚 Lembrete de Devolução

Olá {{ $requisicao->user->name }},

Este é um lembrete de que o prazo de devolução do livro:

**{{ $requisicao->livro->nome }}**

termina amanhã — **{{ $requisicao->data_prevista->format('d/m/Y') }}**.

Por favor, entregue dentro do prazo para evitar penalizações.

Obrigado,  
**Biblioteca Inovcorp**
@endcomponent
