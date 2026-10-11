<?php

namespace App\Http\Controllers\frontend\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function detail_product(Product $product): View
    {
        $product->load('category');

        $similarProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->getKey())
            ->latest()
            ->limit(8)
            ->get();

        return view('pages.product.product-detail', compact('product', 'similarProducts'));
    }
}
