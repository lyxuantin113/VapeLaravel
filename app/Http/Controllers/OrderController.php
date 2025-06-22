<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return auth()->user()->orders()->with('orderItems.product')->latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'voucher_id' => 'nullable|exists:vouchers,id',
        ]);

        $total = 0;

        foreach ($request->items as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $price = $product->sale_price ?? $product->base_price;
            $total += $price * $item['quantity'];
        }

        // Apply voucher
        if ($request->voucher_id) {
            $voucher = \App\Models\Voucher::find($request->voucher_id);
            if (now()->between($voucher->start_date, $voucher->end_date)) {
                $discount = $voucher->discount_type === 'fixed'
                    ? $voucher->discount_value
                    : $total * $voucher->discount_value / 100;
                $total -= $discount;
            }
        }

        $order = auth()->user()->orders()->create([
            'total_price' => $total,
            'voucher_id' => $request->voucher_id,
        ]);

        foreach ($request->items as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $price = $product->sale_price ?? $product->base_price;
            $order->orderItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price_each' => $price,
            ]);
        }

        // Optionally clear cart
        auth()->user()->cartItems()->delete();

        return response()->json(['message' => 'Order placed successfully']);
    }
}
