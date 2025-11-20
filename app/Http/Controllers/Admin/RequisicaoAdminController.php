<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requisicao;
use Illuminate\Http\Request;

class RequisicaoAdminController extends Controller
{
    public function index()
    {
        $requisicoes = Requisicao::with(['user', 'livro'])
            ->orderBy('data_requisicao', 'desc')
            ->get();

        return view('admin.requisicoes.index', compact('requisicoes'));
    }

    // Confirmar entrega (devolução) do livro
    public function confirmarEntrega(Requisicao $requisicao)
    {
        if ($requisicao->estado !== 'pending') {
            return back()->with('error', 'Esta requisição já foi finalizada.');
        }

        $requisicao->update([
            'estado'        => 'returned',
            'data_entrega'  => now(),
        ]);

        // Voltar a marcar o livro como disponível
        $livro = $requisicao->livro;
        if ($livro) {
            $livro->disponivel = true;
            $livro->save();
        }

        return back()->with('success', 'Entrega confirmada com sucesso!');
    }
}
