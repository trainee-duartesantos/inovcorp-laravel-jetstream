<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requisicao;
use Illuminate\Http\Request;

class RequisicaoAdminController extends Controller
{
    public function index(Request $request)
    {
        // 1️⃣ Atualizar automaticamente para "atrasada"
        Requisicao::where('estado', 'ativa')
            ->whereDate('data_prevista', '<', now()->startOfDay())
            ->update(['estado' => 'atrasada']);

        // 2️⃣ Indicadores
        $ativas = Requisicao::where('estado', 'ativa')->count();

        $ultimos30 = Requisicao::whereDate('data_requisicao', '>=', now()->subDays(30))
            ->count();

        $entreguesHoje = Requisicao::where('estado', 'entregue')
            ->whereDate('data_entrega', today())
            ->count();

        $atrasadas = Requisicao::where('estado', 'atrasada')->count();

        // 3️⃣ Filtro pela URL: ?filtro=ativas|atrasadas|entregues|30dias|todas
        $filtro = $request->query('filtro', 'todas');

        $query = Requisicao::with(['user', 'livro'])
            ->orderBy('data_requisicao', 'desc');

        switch ($filtro) {
            case 'ativas':
                $query->where('estado', 'ativa');
                break;

            case 'atrasadas':
                $query->where('estado', 'atrasada');
                break;

            case 'entregues':
                $query->where('estado', 'entregue');
                break;

            case '30dias':
                $query->whereDate('data_requisicao', '>=', now()->subDays(30));
                break;

            case 'todas':
            default:
                // sem filtro extra
                break;
        }

        $requisicoes = $query->get();

        return view('admin.requisicoes.index', compact(
            'requisicoes',
            'ativas',
            'ultimos30',
            'entreguesHoje',
            'atrasadas',
            'filtro'
        ));
    }

    public function show($id)
    {
        $requisicao = Requisicao::with(['livro', 'user'])->findOrFail($id);

        return view('admin.requisicoes.show', compact('requisicao'));
    }

    public function confirmarEntrega($id)
    {
        $requisicao = \App\Models\Requisicao::findOrFail($id);

        if (!in_array($requisicao->estado, ['ativa', 'atrasada'])) {
            return back()->with('error', 'Esta requisição já foi entregue ou cancelada.');
        }

        // Atualiza o estado da requisição
        $requisicao->estado = 'entregue';
        $requisicao->data_entrega = now();
        $requisicao->save();

        // Torna o livro disponível novamente
        if ($requisicao->livro) {
            $requisicao->livro->update([
                'disponivel' => true
            ]);
        }

        return redirect()
            ->route('admin.requisicoes.index')
            ->with('success', 'Entrega confirmada com sucesso!');
    }
}
