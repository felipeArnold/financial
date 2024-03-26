<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Custumer;
use App\Models\Person;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $custumer = Custumer::create([
            'name' => 'Felipe Arnold',
            'document' => '123.456.789-00',
            'avatar' => 'https://via.placeholder.com/150'
        ]);

         \App\Models\User::factory()->create([
             'custumer_id' => $custumer->id,
             'name' => 'Felipe Arnold',
             'email' => 'felipe@example.com',
             'password' => bcrypt('password'),
         ]);

    }
}
