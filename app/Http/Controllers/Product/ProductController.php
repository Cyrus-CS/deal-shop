<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category', 'brand')->orderByDesc('created_at')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::select('name', 'id')->orderByDesc('name')->get();
        $brands = Brand::select('name', 'id')->orderBy('name')->get();
        $product = new Product();
        return view('admin.products.create', compact('product', 'categories', 'brands'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $product = Product::create(Arr::except($validated, ['image', 'images']));
        $this->uploadProductImages($request, $product);

        return redirect()->route('admin.product.index')
            ->with('success', "The product {$product->name} has been successfully created");
    }

    public function edit(Product $product): View
    {
        $categories = Category::select('name', 'id')->orderBy('name')->get();
        $brands = Brand::select('name', 'id')->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();
        $product->update(Arr::except($validated, ['image', 'images']));
        $this->uploadProductImages($request, $product);

        return redirect()->route('admin.product.index')
            ->with('success', "The product {$product->name} has been successfully updated");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteProductImages($product);
        $product->delete();

        return redirect()->route('admin.product.index')
            ->with('success', "The product {$product->name} has been successfully deleted");
    }

    private function uploadProductImages(Request $request, Product $product): void
    {
        $path = public_path('uploads/products');
        File::ensureDirectoryExists($path);

        if ($request->hasFile('image')) {
            $this->deleteImageFile($product->image);
            $product->image = $this->storeResized($request->file('image'), $path, 1024, 1024);
        }

        if ($request->hasFile('images')) {
            foreach ((array) $product->images as $old) {
                $this->deleteImageFile($old);
            }
            $product->images = collect($request->file('images'))
                ->map(fn($file) => $this->storeResized($file, $path, 1024, 1024))
                ->all();
        }

        $product->save();
    }

    private function storeResized(UploadedFile $file, string $path, int $w, int $h): string
    {
        $name = Str::uuid() . '.' . $file->extension();
        Image::read($file)->cover($w, $h)->save("{$path}/{$name}");

        return $name;
    }

    private function deleteImageFile(?string $name): void
    {
        if ($name) {
            File::delete(public_path("uploads/products/{$name}"));
        }
    }

    private function deleteProductImages(Product $product): void
    {
        $this->deleteImageFile($product->image);
        foreach ((array) $product->images as $img) {
            $this->deleteImageFile($img);
        }
    }
}
