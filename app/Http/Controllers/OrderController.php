<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * User có thể:
 * - Xem lịch sử (Danh sách đơn hàng của họ - Để xem lại item và xem trạng thái)
 * - Xóa (Hủy đơn - Chỉ khi trạng thái là Pending)
 */
class OrderController extends Controller
{
    /**
     * Lấy danh sách đơn hàng của user hiện tại cùng sản phẩm trong từng đơn.
     */
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with('orderItems.product')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Xem chi tiết 1 đơn hàng cụ thể.
     */
    public function show($id)
    {
        $order = auth()->user()
            ->orders()
            ->with('orderItems.product')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'note' => 'nullable|string',
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
            'note' => $request->note,
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

    public function cancle($id)
    {
        $order = auth()->user()->orders()->where('id', $id)->firstOrFail();

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Only pending orders can be cancelled.'], 403);
        }

        $order->delete();

        return response()->json(['message' => 'Order cancelled successfully.']);
    }
}
