<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class UserOrdersController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->with('items.livro')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // proteger para evitar acesso a encomendas de outros utilizadores
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        $order->load('items.livro');

        return view('orders.show', compact('order'));
    }
}
