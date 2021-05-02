<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('welcome');
Route::resource('payments', PaymentController::class)->only(['create', 'store', 'show']);
