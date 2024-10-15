<?php

namespace App\Listeners;

use App\Events\PaymentSucceeded;

class UpdateOrderStatusToCancelled
{


    /**
     * Handle the event.
     */
    public function handle(PaymentSucceeded $event)
    {
        $order = $event->order;
        $order->status = 'Paid';
        $order->save();
    }
}
