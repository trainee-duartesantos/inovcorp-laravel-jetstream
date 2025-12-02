<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ReviewSubmittedMail;
use App\Models\Livro;
use App\Models\Review;
use App\Models\Requisicao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReviewController extends Controller
{
    public function store(Request $request, Livro $livro)
    {
        $user = Auth::user(); // em vez de auth()->id() para o Intelephense não se queixar

        $request->validate([
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:1000',
            'requisicao_id' => 'required|exists:requisicoes,id',
        ]);

        $requisicao = Requisicao::findOrFail($request->requisicao_id);

        // Garantir que é a requisição certa, do próprio user e com estado entregue
        if (
            $requisicao->livro_id !== $livro->id ||
            $requisicao->user_id !== $user->id ||
            $requisicao->estado !== 'entregue'
        ) {
            return back()->with('error', 'Só pode avaliar livros já devolvidos e da sua própria requisição.');
        }

        // Impedir 2 reviews para a mesma requisição
        if (Review::where('requisicao_id', $requisicao->id)->exists()) {
            return back()->with('error', 'Já avaliou este livro desta requisição.');
        }

        $review = Review::create([
            'user_id'       => $user->id,
            'livro_id'      => $livro->id,
            'requisicao_id' => $requisicao->id,
            'rating'        => $request->rating,
            'comment'       => $request->comment,
            'status'        => 0, // pendente
        ]);

        // Notificar todos os admins
        $admins = User::whereHas('roles', function ($q) {
            $q->where('slug', 'admin');
        })->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ReviewSubmittedMail($review));
        }

        return back()->with('success', 'Avaliação enviada para aprovação! Aguarde a revisão do administrador.');
    }
}
