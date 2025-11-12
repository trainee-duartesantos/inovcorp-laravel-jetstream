<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
// Rota para exportar livros para CSV - CORRIGIDA
    Route::get('/exportar/livros/csv', function () {
        $livros = \App\Models\Livro::with(['editora', 'autores'])->get(); // 'autores' em vez de 'autors'
        
        $filename = 'livros-biblioteca-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($livros) {
            $handle = fopen('php://output', 'w');
            
            // Adicionar BOM para UTF-8 no Excel
            fputs($handle, "\xEF\xBB\xBF");
            
            // Cabeçalhos
            fputcsv($handle, [
                'ID', 'ISBN', 'Nome do Livro', 'Editora', 'Autores', 
                'Bibliografia', 'Preço (€)', 'Data de Criação'
            ], ';');

            // Dados
            foreach ($livros as $livro) {
                fputcsv($handle, [
                    $livro->id,
                    $livro->isbn,
                    $livro->nome,
                    $livro->editora->nome,
                    $livro->autores->pluck('nome')->join(', '), // 'autores' em vez de 'autors'
                    '"' . str_replace('"', '""', $livro->bibliografia) . '"',
                    number_format($livro->preco, 2, ',', ' '),
                    $livro->created_at->format('d/m/Y H:i')
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    })->name('exportar.livros.csv');