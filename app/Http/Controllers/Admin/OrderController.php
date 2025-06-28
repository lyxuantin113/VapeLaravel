<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

/**
 * Admin có thể:
 * - Lấy danh sách
 * - Cập nhật trạng thái giao
 * - Xóa
 */
class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])->orderByDesc('created_at');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        return $query->paginate(10);
    }

    public function show(Order $order)
    {
        return $order->load(['user', 'orderItems.product']);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipping,shipped,cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        return $order;
    }
}
