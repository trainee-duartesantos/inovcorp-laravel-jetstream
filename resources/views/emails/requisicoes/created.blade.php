@component('mail::message')

# 📚 Requisição Confirmada!

Olá **{{ $requisicao->user->name }}**,  
A sua requisição foi registada com sucesso! 🎉

---

### 🔖 Detalhes da Requisição
- **Nº:** {{ $requisicao->numero }}
- **Livro:** {{ $requisicao->livro->nome }}
- **Data pedido:** {{ $requisicao->data_requisicao->format('d/m/Y') }}
- **Entrega prevista:** {{ $requisicao->data_prevista->format('d/m/Y') }}

@component('mail::panel')
📌 *O livro deve ser devolvido até à data prevista.*
@endcomponent

---

### 📘 Livro Requisitado

@if($requisicao->livro->capa_url)
    <img src="{{ asset('storage/'.$requisicao->livro->capa_url) }}"
         style="width:160px;border-radius:8px;margin-bottom:10px">
@else
    <img src="https://via.placeholder.com/160x240"
         style="border-radius:8px;margin-bottom:10px">
@endif


**Editora:** {{ $requisicao->livro->editora->nome }}  
**Autores:** {{ $requisicao->livro->autores->pluck('nome')->join(', ') }}

---

Obrigado por utilizar a nossa Biblioteca!  
**Inovcorp**

@endcomponent
