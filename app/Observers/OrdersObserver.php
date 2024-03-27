<?php

namespace App\Observers;


use App\Models\Orders;
use Illuminate\Support\Str;

class OrdersObserver
{
    public function created(Orders $order): void
    {
        $number = $order->type === 'service' ? 'OS-' : 'VD-';
        $orderId = 1;

        if (Orders::whereLike('order_number', $number . '%')->count() > 0) {
            $orderId = Orders::max('id') + 1;
        }

        $order->order_number = $number . Str::padLeft($orderId, 5, '0');
        $order->save();
    }
}
