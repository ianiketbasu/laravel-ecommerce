<?php

namespace App\Livewire;

use Livewire\Component;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class Checkout extends Component
{
    public function checkout()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'USD',
                    'product_data' => [
                        'name' => 'Shopping Cart Items',
                    ],
                    'unit_amount' => Auth::user()->cartTotal() * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return redirect($session->url);
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
