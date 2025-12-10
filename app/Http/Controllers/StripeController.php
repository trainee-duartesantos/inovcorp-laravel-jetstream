<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function createStripeSession(Request $request, Order $order)
    {
        if ($order->status === 'pago') {
            return redirect()->route('dashboard')->with('message', 'Este pedido já foi pago.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];

        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => intval($item->preco_unitario * 100),
                    'product_data' => [
                        'name' => $item->livro->nome,
                    ]
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', $order->id),
            'cancel_url' => route('checkout.payment', $order->id),
        ]);

        return redirect()->away($session->url);
    }

    public function success(Order $order)
    {
        // impedir duplicação
        if ($order->status !== 'pago') {
            $order->update([
                'status' => 'pago'
            ]);

            // aqui podemos limpar carrinho...
            auth()->user()->cart?->items()->delete();
        }

        return view('checkout.success', compact('order'));
    }

}
