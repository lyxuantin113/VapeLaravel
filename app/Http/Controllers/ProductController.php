<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Lấy danh sách sản phẩm, có thể lọc theo loại hoặc tìm kiếm
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
            'data' => $query->paginate(12),
        ]);
    }

    // Lấy chi tiết 1 sản phẩm
    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'data' => $product->load('productType'),
        ]);
    }
}
