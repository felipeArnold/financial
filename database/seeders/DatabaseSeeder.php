<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Person;
use App\Models\Vehicles\Vehicles;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FirstUserSeeder::class);
        $this->call(FirstTenantSeeder::class);
        $this->call(PersonSeeder::class);
        $this->call(LeadSeeder::class);
        $this->call(VehiclesSeeder::class);
    }
}
