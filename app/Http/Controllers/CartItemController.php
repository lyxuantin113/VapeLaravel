<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        return auth()->user()->cartItems()->with('product')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = auth()->user()->cartItems()->updateOrCreate(
            ['product_id' => $request->product_id],
            ['quantity' => $request->quantity]
        );

        return response()->json($item, 201);
    }

    public function destroy($id)
    {
        auth()->user()->cartItems()->where('id', $id)->delete();
        return response()->json(['message' => 'Item removed']);
    }
}
