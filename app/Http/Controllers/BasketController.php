<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\Classes\Basket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function basket()
    {
        $order = (new Basket())->getOrder();
        /*$orderId = session('orderId'); принцип DRY - вынесли поиск заказа в конструктор нашего класса Basket
        //if (!is_null($orderId)) { проверка на отсутствия заказа происходит через middleware в basketisnotempty
            $order = Order::findOrFail($orderId);
        //}*/
        return view('basket', compact('order'));
    }

    public function basketPlace()
    {
        $order = (new Basket())->getOrder();
        //$orderId = session('orderId');
        /*if (is_null($orderId)) {
            return redirect()->route('index');
        }*/
        //$order = Order::find($orderId);
        return view('order', compact('order'));
    }

    public function basketConfirm(Request $request)
    {
        $order = (new Basket())->getOrder();
        //$orderId = session('orderId');
        /*if (is_null($orderId)) {
            return redirect()->route('index');
        }*/
        //$order = Order::find($orderId);
        $success =  $order->saveOrder($request->name, $request->phone);

        if ($success) {
            foreach ($order->products as $product)
            {
                $newCountOfProduct = Product::findOrFail($product->id)->count - $product->getOriginal('pivot_count');
                $product->update(['count' => $newCountOfProduct]);
            }

            session()->flash('success', 'Ваш заказ оформлен');
        } else {
            session()->flash('warning', 'Произошла ошибка при оформлении заказа');
        }
        return redirect()->route('index');
    }

    public function basketAdd(/*$productId*/ Product $product) // Model Injection если прописать ожидаемый тип как объект класса тогда с помощью магии вернется объект этого класса
    {
        $orderId = session('orderId');
        if (is_null($orderId)) {
            $order = Order::create();
            session(['orderId' => $order->id]);
        } else {
            $order = Order::find($orderId);
        }

        if ($order->products->contains(/*$productId*/ $product->id)) {
            $pivotRow = $order->products()->where('product_id', /*$productId*/ $product->id)->first()->pivot;

            if (($product->count) <= ($order->products()->where('product_id', $product->id)->first()->pivot->count)) {
                session()->flash('danger', 'Товара '. $product->name . ' больше нет в наличии');
                return redirect()->route('basket');
            }

            $pivotRow->count++;
            $pivotRow->update();
        } else {
            $order->products()->attach(/*$productId*/ $product->id);
        }

        if (Auth::check()) {
            $order->user_id = Auth::id();
            $order->save();
        }

        // $product = Product::find($productId); получается благодаря тому что мы сразу передаем объект, отпадает необходимость в лишнем запросе в базу

        session()->flash('success', 'Товар ' . $product->name . ' добавлен в корзину');

        return redirect()->route('basket');
//        return view('basket', compact('order')); чтобы исправить баг с тем что добавляется еще один товар после обновления нужно использовать редирект
    }

    public function basketRemove(/*$productId*/ Product $product)
    {
        $orderId = session('orderId');
        if (is_null($orderId)) {
            return view('basket', compact('order'));
        }
        $order = Order::find($orderId);
        if ($order->products->contains(/*$productId*/ $product->id)) {
            $pivotRow = $order->products()->where('product_id', /*$productId*/ $product->id)->first()->pivot;
            if ($pivotRow->count < 2) {
                $order->products()->detach(/*$productId*/ $product->id);
            } else {
                $pivotRow->count--;
                $pivotRow->update();
            }
        }

        // $product = Product::find($productId);

        session()->flash('warning', 'Товар ' . $product->name . ' был удален из корзины');


        return redirect()->route('basket');
//        return view('basket', compact('order'));
    }
}
