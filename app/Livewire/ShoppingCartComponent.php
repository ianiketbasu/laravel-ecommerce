<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\Auth;

class ShoppingCartComponent extends Component
{
    public $cartItems = [];
    public $subtotal;
    public $vat;
    public $discount;
    public $total;

    protected $listeners = [
        'cartUpdated' => 'render',
    ];

    public function mount(){
       $this->cartItems = $this->getCartItems();
       $this->calculateTotals();
    }

    // Calculate the totals (subtotal, VAT, discount, total)
    public function calculateTotals()
    {
        $this->subtotal = $this->cartItems->sum(function($item) {
            return $item->quantity * $item->product->price;
        });
        $this->vat = $this->subtotal * 0.1; // Example: 10% VAT
        $this->discount = 5; // Apply your discount logic here
        $this->total = $this->subtotal + $this->vat - $this->discount;
    }

    // Get the cart items for the authenticated user
    public function getCartItems()
    {
        return ShoppingCart::with('product')
            ->where('user_id', Auth::id())
            ->get();
    }

    // Remove an item from the cart
    public function removeItem($cartItemId)
    {
        $cartItem = ShoppingCart::find($cartItemId);
        if ($cartItem) {
            $cartItem->delete();
            $this->dispatch('cartUpdated');
        }
    }

    // Update item quantity in the cart
    public function updateQuantity($cartItemId, $quantity)
    {
        $cartItem = ShoppingCart::find($cartItemId);
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            $this->dispatch('cartUpdated');
        }
    }

    public function render()
    {
        return view('livewire.shopping-cart-component', [
            'cartItems' => $this->cartItems,
            'subtotal' => $this->subtotal,
            'vat' => $this->vat,
            'discount' => $this->discount,
            'total' => $this->total
        ]);
    }
}
