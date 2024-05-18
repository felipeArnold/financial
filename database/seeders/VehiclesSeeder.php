<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\Vehicles\Vehicles;
use Illuminate\Database\Seeder;

class VehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicles::factory(50)->create([
            'tenant_id' => 1,
        ]);
    }
}
