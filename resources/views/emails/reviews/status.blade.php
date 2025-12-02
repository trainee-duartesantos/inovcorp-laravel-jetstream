@component('mail::message')
# 📚 Atualização sobre a sua avaliação

Olá {{ $review->user->name }},

A sua avaliação ao livro **"{{ $review->livro->nome }}"** foi analisada.

@if($review->status == 1)
✅ **Aprovada!**
<br>
A sua opinião já está visível para outros utilizadores.
@else
❌ **Recusada**
@if($review->justification)
<br><br>
> **Motivo:** {{ $review->justification }}
@endif
@endif

Obrigado por contribuir com a comunidade da biblioteca! 📖✨

@component('mail::button', ['url' => url('/livros/'.$review->livro_id)])
Ver livro
@endcomponent

Cumprimentos,<br>
{{ config('app.name') }}
@endcomponent
