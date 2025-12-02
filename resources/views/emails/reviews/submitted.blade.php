@component('mail::message')
# Nova review submetida

Um cidadão submeteu uma nova avaliação.

**Livro:** {{ $review->livro->titulo ?? 'N/A' }}  
**Cidadão:** {{ $review->user->name ?? 'N/A' }}  
**Classificação:** {{ $review->rating }} / 5

@isset($review->comment)
**Comentário:**
> {{ $review->comment }}
@endisset

@component('mail::button', ['url' => url('/admin/reviews')])
Ver reviews pendentes
@endcomponent

Obrigado,  
{{ config('app.name') }}
@endcomponent
