<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function basket()
    {
        $orderId = session('orderId');
        if (!is_null($orderId)) {
            $order = Order::findOrFail($orderId);
        }
        return view('basket', compact('order'));
    }

    public function basketPlace()
    {
        $orderId = session('orderId');
        if (is_null($orderId)) {
            return redirect()->route('index');
        }
        $order = Order::find($orderId);
        return view('order', compact('order'));
    }

    public function basketConfirm(Request $request)
    {
        $orderId = session('orderId');
        if (is_null($orderId)) {
            return redirect()->route('index');
        }
        $order = Order::find($orderId);
        $success =  $order->saveOrder($request->name, $request->phone);

        if ($success) {
            session()->flash('success', 'Ваш заказ оформлен');
        } else {
            session()->flash('warning', 'Произошла ошибка при оформлении заказа');
        }
        return redirect()->route('index');
    }

    public function basketAdd($productId)
    {
        $orderId = session('orderId');
        if (is_null($orderId)) {
            $order = Order::create();
            session(['orderId' => $order->id]);
        } else {
            $order = Order::find($orderId);
        }

        if ($order->products->contains($productId)) {
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            $pivotRow->count++;
            $pivotRow->update();
        } else {
            $order->products()->attach($productId);
        }

        if (Auth::check()) {
            $order->user_id = Auth::id();
            $order->save();
        }

        $product = Product::find($productId);

        session()->flash('success', 'Товар ' . $product->name . ' добавлен в корзину');

        return redirect()->route('basket');
//        return view('basket', compact('order')); чтобы исправить баг с тем что добавляется еще один товар после обновления нужно использовать редирект
    }

    public function basketRemove($productId)
    {
        $orderId = session('orderId');
        if (is_null($orderId)) {
            return view('basket', compact('order'));
        }
        $order = Order::find($orderId);
        if ($order->products->contains($productId)) {
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            if ($pivotRow->count < 2) {
                $order->products()->detach($productId);
            } else {
                $pivotRow->count--;
                $pivotRow->update();
            }
        }

        $product = Product::find($productId);

        session()->flash('warning', 'Товар ' . $product->name . ' был удален из корзины');


        return redirect()->route('basket');
//        return view('basket', compact('order'));
    }
}
