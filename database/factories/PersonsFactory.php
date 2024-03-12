<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\People;

class PersonsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = People::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElement(["physical","legal"]),
            'surname' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'document' => $this->faker->regexify('[A-Za-z0-9]{14}'),
            'birth_date' => $this->faker->date(),
        ];
    }
}
