<?php

namespace App\Http\Controllers\backend\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;

class CategoryController extends Controller
{
    // ------------------------------------- CATEGORY -------------------------------
    public function index() : View{
        $categories = Category::with('products')->orderByDesc('id')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create() : View {
        $category = new Category();
        return view('admin.categories.create', compact('category'));
    }

    public function store(StoreCategoryRequest $request) : RedirectResponse {
        $validated = $request->validated();
        $category = Category::create(Collection::make($validated)->except('image')->toArray());
        $category->image = $request->file('image')->hashName();

        $this->GenerateCategoryThumbailsImage($request->file('image'), $category->image, $request);
        $category->save();
        return redirect()->route('admin.categories.index')->with('success', "the category " . $category->name . "has been sucessfully created");
    }

    public function edit(Category $category) : View{
        return view('admin.categories.edit', [
            'category' => $category
        ]);
    }

    public function update(Category $category, UpdateCategoryRequest $request) : RedirectResponse {
        $validated = $request->validated();
        $category->update(Collection::make($validated)->except('image')->toArray());
        $category->image = $request->file('image')->hashName();

        $this->GenerateCategoryThumbailsImage($request->file('image'), $category->image, $request);
        $category->save();
        return redirect()->route('admin.categories.index')->with('success', "the category " . $category->name . "has been sucessfully updated");
    }

    public function delete(Category $category) : RedirectResponse {
        if($category->image && file_exists(public_path('uploads/categories/' . $category->image))) {
            File::delete(public_path('uploads/categories/' . $category->image));
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', "the category " . $category->name . "has been sucessfully deleted");
    }

    public function GenerateCategoryThumbailsImage(UploadedFile $image, string $imageName, Request $request) : void{
        if(!$request->hasFile('image')) {
            return;
        }
        $destination = public_path('/uploads/categories');
        $image = Image::read($image->path());
        $image->cover(1024,1024, "top");
        $image->resize(300, 300)->save($destination . '/' . $imageName);
    }
}
