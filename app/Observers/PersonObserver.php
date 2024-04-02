<?php

namespace App\Observers;

use App\Models\Person;

class PersonObserver
{

    public function creating(Person $person): void
    {
        if (auth()->check()) {
            $person->custumer_id = auth()->user()->custumer_id;
        }

    }
}
