<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/sales', [App\Http\Controllers\SalesController::class, 'index'])->name('sales');

// Test route to verify akun table connection
Route::get('/test-users', function () {
    $users = User::all();
    return response()->json([
        'count' => $users->count(),
        'users' => $users->map(function($user) {
            return [
                'id' => $user->kd,
                'name' => $user->nama,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role
            ];
        })
    ]);
});
