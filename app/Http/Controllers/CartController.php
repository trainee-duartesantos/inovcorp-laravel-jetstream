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
        // 🚫 VERIFICAÇÃO DE DISPONIBILIDADE
        if (!$livro->disponivel) {
            return back()->with('error', '❌ Este livro não se encontra disponível no momento.');
        }

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
        $user = auth()->user();

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cart->load('items.livro.autores');

        $total = $cart->items->sum(fn($item) => $item->livro->preco);

        return view('cart.index', compact('cart','total'));
    }

    public function remove(CartItem $item)
    {
        if ($item->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Livro removido do carrinho!');
    }

}
