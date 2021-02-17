<?php


namespace App\Classes;

use App\Order;

class Basket
{
    protected $order;

    /**
     * Basket constructor.
     * @param $order
     */
    public function __construct()
    {
        $orderId = session('orderId');
        $this->order = Order::findOrFail($orderId);
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

}
