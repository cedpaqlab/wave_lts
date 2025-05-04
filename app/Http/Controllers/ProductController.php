<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', auth()->id())->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'daily_price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ]);
        $data['user_id'] = auth()->id();
        Product::create($data);
        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'daily_price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ]);
        $product->update($data);
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();
        return redirect()->route('products.index');
    }
} 