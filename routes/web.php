<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::view('/','welcome')->name('welcome');
Route::get('payments/prepare', [PaymentController::class, 'prepare'])->name('payments.prepare');
Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
Route::post('payments', [PaymentController::class, 'send'])->name('payments.send');
