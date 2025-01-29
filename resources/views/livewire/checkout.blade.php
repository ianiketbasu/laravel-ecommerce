<div class="checkout-container">
    <h1 class="text-center">Checkout</h1>

    <div class="cart-summary">
        <p>Subtotal: ${{ $total }}</p>
        <p>VAT: ${{ $total * 0.1 }}</p>
        <p>Total: ${{ $total + ($total * 0.1) }}</p>
    </div>

    <button wire:click="checkout" class="stripe-button">
        Pay with Stripe
    </button>
</div>
