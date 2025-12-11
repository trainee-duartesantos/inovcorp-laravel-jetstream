<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use App\Mail\OrderPaidMail;
use Illuminate\Support\Facades\Mail;

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
        // Só o dono da encomenda pode ver
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403, 'Acesso não autorizado.');
        }

        // Se já estiver pago, não reenviar mail nem alterar
        $primeiraVezPago = false;

        if ($order->status !== 'pago') {
            $order->status = 'pago';
            $order->save();
            $primeiraVezPago = true;
        }

        // Carregar items + livros para o e-mail
        $order->load('items.livro');

        // Só envia e-mail na primeira vez que marca como pago
        if ($primeiraVezPago) {
            Mail::to($order->email)->send(new OrderPaidMail($order));
        }

        return view('checkout.success', compact('order'));
    }

}
