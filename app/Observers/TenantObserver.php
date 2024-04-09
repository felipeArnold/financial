<?php

declare(strict_types=1);

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\{Tenant, User};

class TenantObserver
{

    public function creating(Tenant $tenant): void
    {
        $tenant->slug = Str::slug($tenant->name);
    }

    public function created(Tenant $tenant): void
    {
        $tenant->users()->attach(User::find(1));
    }
}
