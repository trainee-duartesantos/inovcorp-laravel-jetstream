<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LivrosManager;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard protegido
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rotas Admin
    Route::middleware(['auth', 'verified', 'admin'])->group(function () {
        Route::get('/admin/livros', LivrosManager::class)->name('admin.livros');
    });
});

// Export CSV (opção pública ou protegida? Aqui mantenho protegida se quiseres)
Route::middleware(['auth', 'verified', 'admin'])->get('/exportar/livros/csv', function () {
    $livros = \App\Models\Livro::with(['editora', 'autores'])->get();

    $filename = 'livros-biblioteca-' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    $callback = function() use ($livros) {
        $handle = fopen('php://output', 'w');
        fputs($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
            'ID', 'ISBN', 'Nome', 'Editora', 'Autores',
            'Bibliografia', 'Preço (€)', 'Criado em'
        ], ';');

        foreach ($livros as $livro) {
            fputcsv($handle, [
                $livro->id,
                $livro->isbn,
                $livro->nome,
                $livro->editora->nome,
                $livro->autores->pluck('nome')->join(', '),
                '"' . str_replace('"', '""', $livro->bibliografia) . '"',
                number_format($livro->preco, 2, ',', ' '),
                $livro->created_at->format('d/m/Y H:i')
            ], ';');
        }

        fclose($handle);
    };

    return response()->stream($callback, 200, $headers);
})->name('exportar.livros.csv');
