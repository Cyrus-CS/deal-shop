<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware('auth')->group(function(){
    // TestQWERTY123 => Password for Eben - eben@gmail.com
    Route::get('/account-dashboard', [UserController::class, 'index'])
        ->name('user.index');
});

Route::middleware(['auth', AuthAdmin::class])->group(function(){
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin-dashboard/brands', [AdminController::class, 'brands'])->name('admin.brands.index');
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';