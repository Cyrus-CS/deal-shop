<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index() : View
    {
        $products = Product::with('category')->orderByDesc('created_at')->paginate(10);
        return view('pages.shop.index', compact('products'));
    }
}
