<?php

namespace App\Http\Middleware;

use App\Order;
use Closure;

class BasketIsNotEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $order = session('order');
        if (!is_null($order) && $order->getFullSum() > 0) {
            //меняется логика для оптимизации количества запросов к базе
            /*$order = Order::findOrFail($orderId);
            if ($order->products->count() == 0) {
                session()->flash('warning', 'Корзина пуста');
//                return back(); не работает ввиду того что при удалении всех товаров, происходит бесконечный цикл переходов назад
                return redirect()->route('index');
            }*/
            return $next($request);
        } /*else {
            session()->flash('warning', __('basket.basket_is_empty'));
//            return back();
            return redirect()->route('index');
        }*/
//        return $next($request);
            session()->forget('order');
            session()->flash('warning', 'Ваша корзина пуста!');
            return redirect()->route('index');
    }
}
