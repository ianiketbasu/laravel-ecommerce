<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\ShoppingCart;

class Checkout extends Component
{
    public $cartItems;
    public $total;

    public function mount()
    {
        $this->cartItems = ShoppingCart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $this->total = $this->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    // Stripe Checkout Process
    public function checkout()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];

        foreach ($this->cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $item->product->name],
                    'unit_amount' => $item->product->price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return redirect()->to($checkoutSession->url);
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
