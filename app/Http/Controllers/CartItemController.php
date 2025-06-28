<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Customer có thể:
 * 
 * - Thêm, xóa sản phẩm
 * - Chỉnh số lượng từng món khi đã thêm
 */
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = auth()->user()->cartItems()->where('id', $id)->firstOrFail();
        $item->update(['quantity' => $request->quantity]);

        return response()->json($item);
    }
    public function destroy($id)
    {
        auth()->user()->cartItems()->where('id', $id)->delete();
        return response()->json(['message' => 'Item removed']);
    }
}
