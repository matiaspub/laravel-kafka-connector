<?php

namespace App\Http\Controllers;

use App\Jobs\ProductCreated;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response($products);
    }

    public function store(Request $request)
    {
        $product = Product::create($request->only(['product_name', 'product_stock']));
        dispatch(new ProductCreated($product->toArray()));
        return response($product);
    }
}
