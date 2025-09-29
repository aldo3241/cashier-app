<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cashierController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cashier', [cashierController::class, 'index']);
