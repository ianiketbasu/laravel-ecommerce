<?php

use App\Livewire\AboutUs;
use App\Livewire\AddCategory;
use App\Livewire\AllProducts;
use App\Livewire\Contacts;
use App\Livewire\EditProduct;
use App\Livewire\ManageOrders;
use App\Livewire\ManageProduct;
use App\Livewire\AddProductForm;
use App\Livewire\AdminDashboard;
use App\Livewire\ProductDetails;
use App\Livewire\ManageCategories;
use Illuminate\Support\Facades\Route;
use App\Livewire\ShoppingCartComponent;
use App\Livewire\Checkout;
use App\Livewire\PaymentComponent;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/product/{product_id}/details', ProductDetails::class);

Route::get('/all/products', AllProducts::class);

Route::get('/about', AboutUs::class);

Route::get('/contacts', Contacts::class);

Route::get('/shopping-cart', ShoppingCartComponent::class)->name('shopping-cart');

Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin/dashboard', AdminDashboard::class)->name('dashboard');

    Route::get('/products', ManageProduct::class)->name('products');

    Route::get('/orders', ManageOrders::class)->name('orders');

    Route::get('/add/product', AddProductForm::class);

    Route::get('/manage/categories', ManageCategories::class);
    //adding category form
    Route::get('/add/category', AddCategory::class);
    //editing products
    Route::get('/edit/{id}/product', EditProduct::class);
});
Route::get('/payment', PaymentComponent::class)->name('payment');
Route::get('/checkout', Checkout::class)->name('checkout');
Route::get('/checkout/success', function () {
    return view('checkout-success');
})->name('checkout.success');

Route::get('/checkout/cancel', function () {
    return view('checkout-cancel');
})->name('checkout.cancel');

// Route::get('/cart', ShoppingCartComponent::class)->name('cart');

// // Checkout Route
// Route::get('/checkout', Checkout::class)->name('checkout.page');
// Route::post('/checkout', [Checkout::class, 'checkout'])->name('checkout.submit');