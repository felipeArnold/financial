<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user): void
    {
        $user->custumer_id = auth()->user()->custumer_id;
    }
}
