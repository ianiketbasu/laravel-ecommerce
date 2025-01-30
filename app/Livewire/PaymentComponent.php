<?php

namespace App\Livewire;

use Livewire\Component;
use Stripe\Stripe;
use Stripe\PaymentMethod;
use Illuminate\Support\Facades\Auth;

class PaymentComponent extends Component
{
    public $amount, $cardNumber, $cardExpiryMonth, $cardExpiryYear, $cardCVC;

    protected $rules = [
        'amount' => 'required|numeric|between:5,500',
        'cardNumber' => 'required|regex:/^[45]\d{15}$/',
        'cardExpiryMonth' => 'required|numeric|between:1,12',
        'cardExpiryYear' => 'required|numeric|digits:4',
        'cardCVC' => 'required|numeric|digits:3',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private function makeStripePayment()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentMethod = PaymentMethod::create([
            'type' => 'card',
            'card' => [
                'number' => $this->cardNumber,
                'exp_month' => $this->cardExpiryMonth,
                'exp_year' => $this->cardExpiryYear,
                'cvc' => $this->cardCVC,
            ],
        ]);

        return $paymentMethod->id;
    }

    public function makePayment()
    {
        $this->validate();

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            Auth::user()->charge($this->amount * 100, $this->makeStripePayment(), [
                'currency' => 'USD',
                'description' => "Deposit by " . Auth::user()->name,
                'receipt_email' => Auth::user()->email,
            ]);

            Auth::user()->increment('balance', $this->amount);

            session()->flash('success', 'Added $' . $this->amount . ' to your account!');
            $this->resetForm();

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->amount = null;
        $this->cardNumber = null;
        $this->cardExpiryMonth = null;
        $this->cardExpiryYear = null;
        $this->cardCVC = null;
    }

    public function render()
    {
        return view('livewire.payment-component');
    }
}
