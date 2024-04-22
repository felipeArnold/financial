<?php

namespace Database\Seeders;

use App\Enums\Tenant\PlansEnum;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class FirstTenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::create([
            'name' => 'WSoft',
            'slug' => 'w-soft',
            'plans' => PlansEnum::PREMIUM,
        ])->users()->attach(User::find(1));
    }
}
