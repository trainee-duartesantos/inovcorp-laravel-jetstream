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

    public function show($id)
    {
        $requisicao = Requisicao::with(['livro', 'user'])->findOrFail($id);
        return view('admin.requisicoes.show', compact('requisicao'));
    }

    public function entregar($id)
    {
        $requisicao = Requisicao::findOrFail($id);

        if ($requisicao->estado !== 'ativa') {
            return back()->with('error', 'Esta requisição já não está ativa.');
        }

        $requisicao->estado = 'entregue';
        $requisicao->data_entrega = now();
        $requisicao->save();

        // tornar o livro disponível novamente
        $requisicao->livro->update(['disponivel' => true]);

        return redirect()->route('admin.requisicoes.index')
            ->with('success', 'Entrega confirmada com sucesso!');
    }
}
