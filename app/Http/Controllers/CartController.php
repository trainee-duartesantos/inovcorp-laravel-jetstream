<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Livro;

class CartController extends Controller
{
    private function getCart()
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }

        $session = session()->getId();

        return Cart::firstOrCreate(['session_id' => $session]);
    }

    public function add(Livro $livro)
    {
        $cart = $this->getCart();

        $item = CartItem::where('cart_id', $cart->id)
            ->where('livro_id', $livro->id)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'livro_id' => $livro->id,
                'quantity' => 1
            ]);
        }

        return back()->with('success', 'Livro adicionado ao carrinho 🛒');
    }

    public function index()
    {
        $cart = $this->getCart();

        $items = $cart->items()->with('livro')->get();

        return view('cart.index', compact('items'));
    }
}
