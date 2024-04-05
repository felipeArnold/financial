<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FirstUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Felipe Arnold',
            'email' => 'felipe@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
