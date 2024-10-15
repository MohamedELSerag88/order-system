<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/payment', function () {
    $order = \App\Models\Order::first();
    event(new \App\Events\PaymentSucceeded($order));
    return view('payment');
})->name('payment.result');
