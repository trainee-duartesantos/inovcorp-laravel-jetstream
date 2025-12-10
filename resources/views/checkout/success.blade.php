@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <h1>🎉 Pagamento concluído!</h1>
    <p>Obrigado pela sua compra.</p>

    <a href="{{ route('dashboard') }}" class="btn btn-success mt-4">
        Ir para a Dashboard
    </a>
</div>
@endsection
