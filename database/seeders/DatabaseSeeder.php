<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Person;
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

        Person::factory(50)->create([
            'tenant_id' => 1,
        ]);
    }
}
