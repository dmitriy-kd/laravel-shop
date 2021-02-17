<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::active()->paginate(5);
        return view('auth.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // $products = $order->products()->withTrashed()->get(); - получение продуктов удаленных через soft delete
        return view('auth.orders.show', compact('order'));
    }
}
