<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequisicaoController extends Controller
{
    // Lista de requisições do utilizador autenticado
    public function index()
    {
        $user = Auth::user();

        $requisicoes = Requisicao::with(['livro'])
            ->where('user_id', $user->id)
            ->orderBy('data_requisicao', 'desc')
            ->get();

        return view('requisicoes.index', compact('requisicoes'));
    }

    // Criar nova requisição
    public function store(Request $request)
    {
        $request->validate([
            'livro_id' => 'required|exists:livros,id',
        ]);

        $user = Auth::user();
        $livro = Livro::findOrFail($request->livro_id);

        // 1️⃣ Verificar se o livro está requisitado
        $jaRequisitado = Requisicao::where('livro_id', $livro->id)
            ->where('estado', 'ativa')
            ->exists();

        if ($jaRequisitado || !$livro->disponivel) {
            return back()->with('error', 'Este livro não está disponível para requisição.');
        }

        // 2️⃣ Verificar limite de 3 requisições
        if ($user->requisicoes()->where('estado', 'ativa')->count() >= 3) {
            return back()->with('error', 'Já atingiu o limite de 3 livros requisitados.');
        }

        // 3️⃣ Número sequencial
        $ultimo = Requisicao::orderBy('id', 'desc')->first();
        $novoNumero = 'R-' . str_pad(($ultimo->id ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        // 4️⃣ Criar requisição
        Requisicao::create([
            'numero'         => $novoNumero,
            'user_id'        => $user->id,
            'livro_id'       => $livro->id,
            'data_requisicao'=> now(),
            'data_prevista'  => now()->addDays(5),
            'estado'         => 'ativa',
        ]);

        // 5️⃣ Atualizar disponibilidade
        $livro->disponivel = false;
        $livro->save();

        return back()->with('success', 'Requisição criada com sucesso!');
    }

}
