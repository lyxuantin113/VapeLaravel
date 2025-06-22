<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'stars' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'image_path' => 'nullable|string',
        ]);

        auth()->user()->ratings()->create($request->all());

        return response()->json(['message' => 'Rated successfully']);
    }

    public function index(Request $request)
    {
        $productId = $request->product_id;

        return \App\Models\Rating::with('user')
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->latest()
            ->paginate(10);
    }
}
