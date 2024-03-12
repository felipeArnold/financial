<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Addresses;

class AddressesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Addresses::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'street' => $this->faker->streetName(),
            'number' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'complement' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'district' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'city' => $this->faker->city(),
            'state' => $this->faker->regexify('[A-Za-z0-9]{2}'),
            'country' => $this->faker->country(),
            'zip_code' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'morphs' => $this->faker->word(),
        ];
    }
}
