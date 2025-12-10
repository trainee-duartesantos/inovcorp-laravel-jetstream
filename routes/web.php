<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LivrosManager;
use App\Livewire\AutoresManager;
use App\Livewire\EditorasManager;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\Admin\RequisicaoAdminController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\Admin\GoogleBooksController;
use App\Http\Controllers\Admin\ReviewAdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StripeController;



Route::get('/welcome', function () {
    return view('welcome');
});
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/welcome');
})->name('logout');


// Dashboard protegido
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->name('dashboard');

    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/user/profile', function () {
        return view('profile.show');
    })->name('profile.show');

    Route::get('/livros', [LivroController::class, 'index'])->name('livros.index');
    Route::get('/livros/{livro}', [LivroController::class, 'show'])->name('livros.show');
    // Rotas Admin
    Route::middleware(['admin'])->group(function () {

        Route::get('/admin/livros', LivrosManager::class)->name('admin.livros');
        Route::get('/admin/autores', AutoresManager::class)->name('admin.autores');
        Route::get('/admin/editoras', EditorasManager::class)->name('admin.editoras');

        Route::get('/admin/requisicoes', [RequisicaoAdminController::class, 'index'])
            ->name('admin.requisicoes.index');

        Route::get('/admin/requisicoes/{id}', [RequisicaoAdminController::class, 'show'])
            ->name('admin.requisicoes.show');

        Route::post('/admin/requisicoes/{requisicao}/entregar',
            [RequisicaoAdminController::class, 'confirmarEntrega'])
            ->name('admin.requisicoes.entregar');

        Route::get('/admin/google-books', [GoogleBooksController::class, 'search'])
            ->name('admin.googlebooks.search');

        Route::post('/admin/google-books/import', [GoogleBooksController::class, 'import'])
            ->name('admin.googlebooks.import');

        // Admin - Moderação de Reviews
        Route::get('/admin/reviews', [ReviewAdminController::class, 'index'])
            ->name('admin.reviews.index');

        Route::put('/admin/reviews/{review}', [ReviewAdminController::class, 'updateStatus'])
            ->name('admin.reviews.update');

        Route::get('/admin/utilizadores', [\App\Http\Controllers\Admin\UserController::class, 'index'])
            ->name('admin.utilizadores');

        Route::post('/admin/utilizadores/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])
            ->name('admin.utilizadores.updateRole');


        // ✅ NOVA ROTA: teste simples à Google Books API
        Route::get('/admin/google-books/test', [GoogleBooksController::class, 'test'])
            ->name('admin.googlebooks.test');
    });

        // Rotas Cidadão/Admin (próprio utilizador)
        Route::get('/requisicoes', [RequisicaoController::class, 'index'])
            ->name('requisicoes.index');

        Route::post('/requisicoes', [RequisicaoController::class, 'store'])
            ->name('requisicoes.store');

        Route::post('/livros/{livro}/review', [LivroController::class, 'review'])
            ->name('livros.review')
            ->middleware('auth');

        Route::post('/livros/{livro}/alerta', [LivroController::class, 'alerta'])
            ->middleware('auth')
            ->name('livros.alerta');

        Route::post('/carrinho/{livro}/adicionar', [CartController::class, 'add'])
            ->name('cart.add');

        Route::get('/carrinho', [CartController::class, 'index'])
            ->name('cart.index');

        Route::delete('/carrinho/{item}/remover', [CartController::class, 'remove'])
            ->middleware('auth')
            ->name('cart.remove');

    Route::middleware(['auth'])->group(function(){

        Route::get('/checkout/morada', [CheckoutController::class, 'address'])
            ->name('checkout.address');

        Route::post('/checkout/morada', [CheckoutController::class, 'storeAddress'])
            ->name('checkout.address.store');

        Route::get('/checkout/pagamento/{order}', [CheckoutController::class, 'payment'])
            ->name('checkout.payment');

        Route::post('/checkout/stripe/{order}', [StripeController::class, 'createStripeSession'])
            ->name('checkout.stripe');

        Route::get('/checkout/sucesso/{order}', [CheckoutController::class, 'success'])
            ->name('checkout.success');

    });



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
