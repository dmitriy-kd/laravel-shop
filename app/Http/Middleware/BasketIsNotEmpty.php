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
        $orderId = session('orderId');

        if (!is_null($orderId)) {
            $order = Order::findOrFail($orderId);
            if ($order->products->count() == 0) {
                session()->flash('warning', 'Корзина пуста');
//                return back(); не работает ввиду того что при удалении всех товаров, происходит бесконечный цикл переходов назад
                return redirect()->route('index');
            }
        } else {
            session()->flash('warning', 'Корзина пуста');
//            return back();
            return redirect()->route('index');
        }
        return $next($request);
    }
}
