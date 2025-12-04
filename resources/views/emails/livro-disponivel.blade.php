<p>Olá!</p>

<p>O livro <strong>{{ $livro->nome }}</strong> já está disponível para requisição.</p>

<p>Pode requisitá-lo aqui:</p>

<a href="{{ route('livros.show', $livro->id) }}">
    {{ route('livros.show', $livro->id) }}
</a>

<p>Bom estudo e boas leituras 📚</p>
<p>Biblioteca Inovcorp</p>
