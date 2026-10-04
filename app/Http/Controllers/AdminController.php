<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\View\View;


class AdminController extends Controller
{
    public function index() : View{
        return view('admin.index');
    }

    public function brands() : View{ 
        $brands = Brand::orderByDesc('id')->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }
}
