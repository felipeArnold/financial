<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user): void
    {
        if (auth()->check()) {
            $user->custumer_id = auth()->user()->custumer_id;
        }
    }
}
