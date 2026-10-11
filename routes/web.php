<?php

use App\Http\Controllers\backend\Brand\BrandController;
use App\Http\Controllers\backend\Category\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\backend\Product\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [\App\Http\Controllers\frontend\Shop\ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}', [\App\Http\Controllers\frontend\Product\ProductController::class, 'detail_product'])->name('product.detail');

Route::middleware('auth')->group(function(){
    // TestQWERTY123 => Password for Eben - eben@gmail.com
    Route::get('/account', [UserController::class, 'index'])
        ->name('user.index');
});

Route::middleware(['auth', AuthAdmin::class])->prefix('/admin-dashboard')
    ->controller(BrandController::class)->name('admin.')->group(function(){

        // ---------------------- BRAND --------------------------------
        Route::get('/', 'index')->name('index');
        Route::get('/brand', 'brands')->name('brands.index');
        Route::get('/new', 'add_brand')->name('brands.create');
        Route::post('/new', 'storeBrand')->name('brands.store');
        Route::get('/edit/{brand}', 'add_edit')->name('brand.edit');
        Route::post('/edit/{brand}', 'updateBrand')->name('brand.update');
        Route::delete('/delete/{brand}', 'delete')->name('brand.delete');
});

Route::middleware(['auth', AuthAdmin::class])->prefix('/admin-dashboard/category')
    ->name('admin.')->controller(CategoryController::class)->group(function(){

        // --------------------------- CATEGORY -------------------------
        Route::get('/', 'index')->name('categories.index');
        Route::get('/new', 'create')->name('categories.create');
        Route::post('new', 'store')->name('categories.store');
        Route::get('/edit/{category}', 'edit')->name('categories.edit');
        Route::post('/edit/{category}', 'update')->name('categories.update');
        Route::delete('/{category}', 'delete')->name('categories.delete');
});

// --------------------------  PRODUCT ------------------------------
Route::middleware(['auth', AuthAdmin::class])->name('admin.')
    ->prefix('/admin-dashboard')->group(function(){
        Route::resource('product', ProductController::class)->except('show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';