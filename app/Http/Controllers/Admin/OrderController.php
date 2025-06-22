<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with(['user', 'orderItems.product'])->orderByDesc('created_at')->paginate(10);
    }

    public function show(Order $order)
    {
        return $order->load(['user', 'orderItems.product']);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        return $order;
    }
}
