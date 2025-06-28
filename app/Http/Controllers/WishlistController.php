<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Customer có thể:
 * 
 * - Thêm, xóa item ở wishlist
 */
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
        $wishlistItem = auth()->user()->wishlist()->where('id', $id)->firstOrFail();
        $wishlistItem->delete();

        return response()->json(['message' => 'Removed from wishlist']);
    }
}
