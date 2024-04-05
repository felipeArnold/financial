<?php

namespace Database\Factories;

use App\Models\Emails;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Emails::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'address' => $this->faker->word(),
            'morphs' => $this->faker->word(),
        ];
    }
}
