<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Confirmação de Encomenda</title>
</head>
<body>
    <h2>Olá {{ $order->nome }},</h2>

    <p>Obrigado pela sua compra na <strong>Biblioteca Inovcorp</strong> 📚</p>

    <p>
        A sua encomenda <strong>#{{ $order->id }}</strong> foi marcada como
        <strong>{{ strtoupper($order->status) }}</strong>.
    </p>

    <h3>Detalhes da encomenda</h3>

    <ul>
        <li><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</li>
        <li>
            <strong>Total:</strong>
            {{ number_format($order->total, 2, ',', '.') }} €
        </li>
        <li>
            <strong>Estado:</strong> {{ ucfirst($order->status) }}
        </li>
    </ul>

    <h3>Livros</h3>
    <ul>
        @foreach($order->items as $item)
            <li>
                {{ $item->livro->nome }} — {{ $item->quantity }} x
                {{ number_format($item->preco_unitario, 2, ',', '.') }} €
            </li>
        @endforeach
    </ul>

    <h3>Morada de entrega</h3>
    <p>
        {{ $order->nome }}<br>
        {{ $order->morada }}<br>
        {{ $order->codigo_postal }} {{ $order->cidade }}<br>
        Telefone: {{ $order->telefone ?? '—' }}
    </p>

    <p style="margin-top: 20px;">
        Se não realizou esta compra ou tiver alguma dúvida, por favor contacte-nos.
    </p>

    <p>
        Cumprimentos,<br>
        <strong>Equipa Biblioteca Inovcorp</strong>
    </p>
</body>
</html>
