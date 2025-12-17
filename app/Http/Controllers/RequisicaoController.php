<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\RequisicaoCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogger;



class RequisicaoController extends Controller
{
    // Lista de requisições do utilizador autenticado
    public function index()
    {
        $user = Auth::user();

        $ativas = $user->requisicoes()
            ->where('estado', 'ativa')
            ->with('livro')
            ->latest()
            ->get();

        $entregues = $user->requisicoes()
            ->where('estado', 'entregue')
            ->with('livro')
            ->latest()
            ->get();

        $canceladas = $user->requisicoes()
            ->whereIn('estado', ['cancelada', 'atrasada'])
            ->with('livro')
            ->latest()
            ->get();

        return view('requisicoes.index', compact('ativas', 'entregues', 'canceladas'));
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
        if (!$livro->disponivel) {
            return back()->with('error', 'Este livro não está disponível para requisição.');
        }

        $jaRequisitado = Requisicao::where('livro_id', $livro->id)
            ->where('estado', 'ativa')
            ->exists();

        if ($jaRequisitado) {
            return back()->with('error', 'Este livro já se encontra requisitado.');
        }

        // 2️⃣ Verificar limite de 3 requisições
        if ($user->requisicoes()->where('estado', 'ativa')->count() >= 3) {
            return back()->with('error', 'Já atingiu o limite de 3 livros requisitados.');
        }

        // 3️⃣ Número sequencial
        $ultimo = Requisicao::orderBy('id', 'desc')->first();
        $novoNumero = 'R-' . str_pad(($ultimo->id ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        // 4️⃣ Criar requisição
        $requisicao = Requisicao::create([
            'numero'         => $novoNumero,
            'user_id'        => $user->id,
            'livro_id'       => $livro->id,
            'data_requisicao'=> now(),
            'data_prevista'  => now()->addDays(5),
            'estado'         => 'ativa',
        ]);

        // 📩 1️⃣ Email para o cidadão
        Mail::to($user->email)->send(new RequisicaoCreatedMail($requisicao));

        // Temporariamente sem envio para admin, para evitar erro Mailtrap
        Log::info("Envio a admin desativado para evitar limite Mailtrap.");


        // 5️⃣ Atualizar disponibilidade
        $livro->disponivel = false;
        $livro->save();

        return back()->with('success', 'Requisição criada com sucesso!');
    }

    public function devolver(Requisicao $requisicao)
    {
        // Garantir que só o dono pode devolver
        if ($requisicao->user_id !== auth()->id()) {
            abort(403);
        }

        $requisicao->update([
            'estado' => 'entregue',
            'data_entrega' => now(),
        ]);

        $requisicao->livro->update([
            'disponivel' => true,
        ]);

        AuditLogger::log(
            module: 'requisicoes',
            action: 'returned',
            objectId: $requisicao->id,
            changes: [
                'estado' => ['old' => 'ativa', 'new' => 'entregue'],
            ]
        );

        // JSON
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Livro devolvido com sucesso.');
    }
}
