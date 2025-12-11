<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminEncomendasController extends Controller
{
    public function index()
    {
        $encomendas = Order::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.encomendas.index', compact('encomendas'));
    }

    public function show(Order $order)
    {
        $order->load('items.livro', 'user');  // Carregar relações

        return view('admin.encomendas.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pendente,pago,cancelado'
        ]);

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Estado atualizado com sucesso!');
    }

}
