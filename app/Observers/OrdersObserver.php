<?php

namespace App\Observers;


use App\Models\Orders;
use Illuminate\Support\Str;

class OrdersObserver
{
    public function created(Orders $order): void
    {
        $number = $order->type === 'service' ? 'OS-' : 'VD-';
        $orderId = Orders::whereLike('order_number', $number . '%')->count() + 1;
        $order->order_number = $number . Str::padLeft($orderId, 5, '0');
        $order->save();
    }
}
