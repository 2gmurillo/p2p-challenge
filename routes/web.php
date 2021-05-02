<?php

use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

App::setLocale('es');

Route::view('/','welcome')->name('welcome');
Route::get('purchases/prepare', [PurchaseController::class, 'prepare'])->name('purchases.prepare');
Route::get('purchases/{payment}', [PurchaseController::class, 'show'])->name('purchases.show');
Route::post('purchases', [PurchaseController::class, 'pay'])->name('purchases.pay');
