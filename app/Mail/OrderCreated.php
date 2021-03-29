<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $order;

    /**
     * OrderCreated constructor.
     * @param $name
     */
    public function __construct($name, $order)
    {
        $this->name = $name;
        $this->order = $order;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $orderId = $this->order->id;
        $orderSum = $this->order->calculateFullSum();
        $orderProducts = $this->order->products;

        return $this->view('mail.order_created', [
            'name' => $this->name,
            'orderId' => $orderId,
            'orderSum' => $orderSum,
            'orderProducts' => $orderProducts,
        ]);
    }
}
