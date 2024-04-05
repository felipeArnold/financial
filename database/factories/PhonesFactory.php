<?php

namespace Database\Factories;

use App\Models\Phones;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhonesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Phones::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->regexify('[A-Za-z0-9]{15}'),
            'morphs' => $this->faker->word(),
        ];
    }
}
