<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Admin có thể:
 * - CRUD sản phẩm
 */
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('productType');

        if ($request->filled('type')) {
            $query->where('product_type_id', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json([
            'success' => true,
            'data' => $query,
        ]);
    }

    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'data' => $product->load('productType'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'product_type_id' => 'required|exists:product_types,id',
            'description' => 'nullable|string',
            'info' => 'nullable|array', // Laravel sẽ cast tự động nếu $casts['info'] = 'array'
            'base_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'product_type_id' => 'sometimes|exists:product_types,id',
            'description' => 'nullable|string',
            'info' => 'nullable|array', // nên dùng array thay vì json
            'base_price' => 'sometimes|numeric',
            'sale_price' => 'nullable|numeric',
            'quantity' => 'sometimes|integer|min:0',
        ]);

        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Product updated',
            'data' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted',
            'data' => $product,
        ]);
    }
}
