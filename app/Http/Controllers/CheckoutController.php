<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function address()
    {
        return view('checkout.address');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'nome'           => 'required|string|max:100',
            'email'          => 'required|email|max:100',
            'morada'         => 'required|string|max:255',
            'cidade'         => 'required|string|max:100',
            'codigo_postal'  => 'required|string|max:20',
            'telefone'       => 'nullable|string|max:20',
        ]);

        $cart = Cart::where('user_id', auth()->id())->with('items.livro')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'O seu carrinho está vazio.');
        }

        // calcular total do carrinho
        $total = 0;
        foreach ($cart->items as $item) {
            $total += $item->livro->preco * $item->quantity;
        }

        // criar encomenda
        $order = Order::create([
            'user_id'       => auth()->id(),
            'nome'          => $request->nome,
            'email'         => $request->email,
            'morada'        => $request->morada,
            'cidade'        => $request->cidade,
            'codigo_postal' => $request->codigo_postal,
            'telefone'      => $request->telefone,
            'total'         => $total,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'livro_id' => $item->livro_id,
                'quantity' => $item->quantity,
                'preco_unitario' => $item->livro->preco,
                'subtotal' => $item->livro->preco * $item->quantity,
            ]);
        }

        // LIMPAR carrinho após checkout
        $cart->items()->delete();
        $cart->delete();

        // Enviar para Stripe
        return redirect()->route('checkout.payment', $order->id);
    }

    public function payment(Order $order)
    {
        return view('checkout.payment', compact('order'));
    }

    public function success(Order $order)
    {
        $order->status = 'pago';
        $order->save();

        return view('checkout.success', compact('order'));
    }

}
