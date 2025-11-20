<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LivrosManager;
use App\Livewire\AutoresManager;
use App\Livewire\EditorasManager;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\Admin\RequisicaoAdminController;
use App\Http\Controllers\LivroController;

Route::get('/welcome', function () {
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

    Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
    Route::get('/livros/{livro}', [LivroController::class, 'show'])->name('livros.show');
    // Rotas Admin
    Route::middleware(['admin'])->group(function () {

        Route::get('/admin/livros', LivrosManager::class)->name('admin.livros');
        Route::get('/admin/autores', AutoresManager::class)->name('admin.autores');
        Route::get('/admin/editoras', EditorasManager::class)->name('admin.editoras');

        Route::get('/admin/requisicoes', [RequisicaoAdminController::class, 'index'])
            ->name('admin.requisicoes.index');

        Route::patch('/admin/requisicoes/{requisicao}/entregar',
            [RequisicaoAdminController::class, 'confirmarEntrega'])
            ->name('admin.requisicoes.entregar');
    });

    // Rotas Cidadão/Admin (próprio utilizador)
    Route::get('/requisicoes', [RequisicaoController::class, 'index'])
        ->name('requisicoes.index');

    Route::post('/requisicoes', [RequisicaoController::class, 'store'])
        ->name('requisicoes.store');

}); // 👈 ESTE ESTAVA A FALTAR

// Export CSV
Route::middleware(['auth', 'verified', 'admin'])
    ->get('/exportar/livros/csv', function () {

        $livros = \App\Models\Livro::with(['editora', 'autores'])->get();
        $filename = 'livros-biblioteca-'. date('Y-m-d') .'.csv';

        return response()->stream(function() use ($livros) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'ID','ISBN','Nome','Editora','Autores',
                'Bibliografia','Preço (€)','Criado em'
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
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    })->name('exportar.livros.csv');
