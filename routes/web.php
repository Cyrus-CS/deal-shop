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
    Route::get('/account', [UserController::class, 'index'])
        ->name('user.index');
});

Route::middleware(['auth', AuthAdmin::class])->prefix('/admin-dashboard')
    ->controller(AdminController::class)->name('admin.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('/brand', 'brands')->name('brands.index');
        Route::get('/brand/new', 'add_brand')->name('brands.create');
        Route::post('/brand/new', 'storeBrand')->name('brands.store');
        Route::get('/brand/edit/{brand}', 'add_edit')->name('brand.edit');
        Route::post('/brand/edit/{brand}', 'updateBrand')->name('brand.update');
        Route::post('/brand/delete/{brand}', 'delete')->name('brand.delete');
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