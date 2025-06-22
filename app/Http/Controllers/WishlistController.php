<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        return auth()->user()->wishlist()->with('product')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        auth()->user()->wishlist()->firstOrCreate([
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Added to wishlist']);
    }

    public function destroy($id)
    {
        auth()->user()->wishlist()->where('id', $id)->delete();
        return response()->json(['message' => 'Removed from wishlist']);
    }
}
