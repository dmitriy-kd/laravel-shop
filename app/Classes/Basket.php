<?php


namespace App\Classes;

use App\Currency;
use App\Mail\OrderCreated;
use App\Order;
use App\Product;
use App\Services\CurrencyConversion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Basket
{
    protected $order;

    /**
     * Basket constructor.
     * @param bool $createOrder
     */
    public function __construct($createOrder = false)
    {
        /*$orderId = session('orderId'); старый функционал где заказ создавался сразу же при добавлении товаров
        $this->order = Order::findOrFail($orderId);*/

        $order = session('order');

        if (is_null($order) && $createOrder)
        {
            $data = [];
            if (Auth::check()) {
                $data['user_id'] = Auth::id();
            }
            $data['currency_id'] = CurrencyConversion::getCurrentCurrencyFromSession()->id;

            $this->order = new Order($data);
            session(['order' => $this->order]);
        } else {
            $this->order = $order;
        }
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function saveOrder($name, $phone, $email = 'admin')
    {
        if (!$this->countAvailable(true))
        {
            return false;
        }
        $this->order->saveOrder($name, $phone);
        Mail::to($email)->send(new OrderCreated($name, $this->getOrder()));
        return true;
    }

    public function countAvailable($updateCount = false)
    {
        $products = collect([]);
        foreach ($this->order->products as $orderProduct)
        {
            $product= Product::find($orderProduct->id);
            if ($orderProduct->countInOrder > $product->count)
            {
                return false;
            }

            if ($updateCount)
            {
                $product->count -= $orderProduct->countInOrder;
                $products->push($product);
            }
        }

        if ($updateCount)
        {
            $products->map->save();
        }

        return true;
    }

//    protected function getPivotRow($product)
//    {
//        return $this->order->products()->where('product_id', /*$productId*/ $product->id)->first()->pivot;
//    }

    public function addProduct(Product $product)
    {
        if ($this->order->products->contains($product)) {
            $pivotRow = $this->order->products->where('id', $product->id)->first();
            /*if (($product->count) <= ($this->order->products()->where('product_id', $product->id)->first()->pivot->count)) {
                session()->flash('warning', 'Товара ' . $product->name . ' больше нет в наличии');
                return redirect()->route('basket');
            }*/

            if ($pivotRow->countInOrder >= $product->count)
            {
                return false;
            }
//            $pivotRow->update();
            $pivotRow->countInOrder++;
        } else {
            if ($product->count == 0)
            {
                return false;
            }

//            $this->order->products()->attach(/*$productId*/ $product->id);
            $product->countInOrder = 1;
            $this->order->products->push($product);
        }

        return true;
    }

    public function removeProduct(Product $product)
    {
        if ($this->order->products->contains($product)) {
            $pivotRow = $this->order->products->where('id', $product->id)->first();
            if ($pivotRow->countInOrder < 2) {
                $this->order->products->pop($product);
            } else {
                $pivotRow->countInOrder--;
            }
        }

    }

}
