<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function creating(Product $product): void
    {
        if (auth()->check()) {
            $product->custumer_id = auth()->user()->custumer_id;
        }
    }
}
