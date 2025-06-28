<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductType;

/**
 * Admin có thể:
 * - CRUD product cats
 */
class ProductTypeController extends Controller
{
    public function index()
    {
        return ProductType::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        return ProductType::create($data);
    }

    public function show($id)
    {
        $productType = ProductType::findOrFail($id);
        return response()->json($productType);
    }


    public function update(Request $request, ProductType $productType)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $productType->update($data);
        return $productType;
    }

    public function destroy(ProductType $productType)
    {
        $productType->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
