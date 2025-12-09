<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CheckoutController extends Controller
{
    // PASSO 1 — Morada de entrega
    public function address()
    {
        $cart = Cart::where('user_id', auth()->id())
                    ->with('items.livro')
                    ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'O seu carrinho está vazio.');
        }

        return view('checkout.address', compact('cart'));
    }
}
