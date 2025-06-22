<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('productType');

        if ($request->has('type')) {
            $query->where('product_type_id', $request->type);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return $query->paginate(10);
    }

    public function show(Product $product)
    {
        return $product;
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'product_type_id' => 'required|exists:product_types,id',
            'description' => 'nullable|string',
            'info' => 'nullable|json',
            'base_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'quantity' => 'required|integer',
        ]);

        $product = Product::create($data);
        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'product_type_id' => 'sometimes|exists:product_types,id',
            'description' => 'nullable|string',
            'info' => 'nullable|json',
            'base_price' => 'sometimes|numeric',
            'sale_price' => 'nullable|numeric',
            'quantity' => 'sometimes|integer',
        ]);

        $product->update($data);
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
