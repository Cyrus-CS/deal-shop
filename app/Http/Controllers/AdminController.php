<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{

    // ---------------------------------  BRAND -------------------------
    public function index() : View{
        return view('admin.index');
    }

    public function brands() : View{ 
        $brands = Brand::orderByDesc('id')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    public function add_brand() : View{
        $brand = new Brand();
        return view('admin.brands.create', compact('brand'));
    }

    public function add_edit(Brand $brand) : View{
        return view('admin.brands.edit', [
            'brand' => $brand
        ]);
    }

    public function storeBrand(StoreBrandRequest $request) : RedirectResponse {
        $validated = $request->validated();
        $brand = Brand::create(Collection::make($validated)->except('image')->toArray());
        $brand->image = $request->file('image')->hashName();

        $this->GenerateBrandThumbailsImage($request->file('image'), $brand->image, $request);
        $brand->save();
        return redirect()->route('admin.brands.index')->with('success', "the " . $brand->name . " brand has been successfully created");
    }
    public function updateBrand(Brand $brand, UpdateBrandRequest $request) : RedirectResponse {
        $validated = $request->validated();
        $brand->update(Collection::make($validated)->except('image')->toArray());
        $brand->image = $request->file('image')->hashName();

        $this->GenerateBrandThumbailsImage($request->file('image'), $brand->image, $request);
        $brand->save();
        return redirect()->route('admin.brands.index')->with('success', "the " . $brand->name . " brand has been successfully updated");
    }

    public function delete(Brand $brand){
        if($brand->image && file_exists(public_path('uploads/brands/' . $brand->image))) {
            File::delete(public_path('uploads/brands/' . $brand->image));
        }
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', "the " . $brand->name . " brand has been successfully deleted");
    }

    public function GenerateBrandThumbailsImage(UploadedFile $image, string $imageName, Request $request) : void{
        if(!$request->hasFile('image')) {
            return;
        }
        $destination = public_path('/uploads/brands');
        $image = Image::read($image->path());
        $image->cover(1024,1024, "top");
        $image->resize(300, 300)->save($destination . '/' . $imageName);
    }
}