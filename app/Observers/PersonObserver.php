<?php

namespace App\Observers;

use App\Models\Person;

class PersonObserver
{

    public function creating(Person $person): void
    {
        $person->custumer_id = auth()->user()->custumer_id;

    }
}
