<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Devdojo\Auth\Models\User as AuthUser;

class User extends AuthUser
{
    use HasFactory, Notifiable, Billable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'stripe_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Define relationship with ShoppingCart model
    public function cart()
    {
        return $this->hasMany(ShoppingCart::class);
    }

    // Calculate the total price of the cart
    public function cartTotal()
    {
        return $this->cart()->with('product')->get()->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });
    }
}
