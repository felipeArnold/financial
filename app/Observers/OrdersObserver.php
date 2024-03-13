<?php

namespace App\Observers;


use App\Models\Orders;
use Illuminate\Support\Str;

class OrdersObserver
{
    public function created(Orders $model): void
    {
        $orderId = Orders::max('id') + 1;

        $model->order_number = 'ORD-' . Str::padLeft($orderId, 5, '0');
        $model->save();
    }
}
