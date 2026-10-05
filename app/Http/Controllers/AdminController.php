<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\StoreBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index() : View{
        return view('admin.index');
    }

    public function brands() : View{ 
        $brands = Brand::orderByDesc('id')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    public function add_brand() : View{
        return view('admin.brands.create');
    }

    public function storeBrand(StoreBrandRequest $request) {
        $validated = $request->validated();
        $brand = Brand::create(Collection::make($validated)->except('image')->toArray());
        $brand->image = $request->file('image')->hashName();
        $this->GenerateBrandThumbailsImage($request->file('image'), $brand->image, $request);
        $brand->save();
        return redirect()->route('admin.brands.index')->with('success', "the " . $brand->name . " brand has been successfully created");
    }

    public function GenerateBrandThumbailsImage(UploadedFile $image, string $imageName, Request $request) : void{
        if(!$request->hasFile('image')) {
            return;
        }
        $destination = public_path('/uploads/brands');
        $image = Image::read($image->path());
        $image->cover(1024,1024, "top");
        $image->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destination . '/' . $imageName);
    }
}
