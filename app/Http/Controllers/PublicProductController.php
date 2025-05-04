<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PublicProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('name', $slug)->orWhere('id', $slug)->firstOrFail();
        return view('public.product', compact('product'));
    }
} 