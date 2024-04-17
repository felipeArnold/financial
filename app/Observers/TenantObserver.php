<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;

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
