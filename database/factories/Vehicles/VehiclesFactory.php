<?php

namespace Database\Factories\Vehicles;

use Carbon\Carbon;
use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicles\Vehicles>
 */
class VehiclesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['car', 'motorcycle', 'truck']),
            'plate' => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{4}'),
            'owner_id' => PersonFactory::new(['tenant_id' => 1]),
            'model_id' => VehiclesModelsFactory::new(),
            'year' => $this->faker->year,
            'mileage' => $this->faker->numberBetween(0, 100000),
            'price_sale' => $this->faker->randomFloat(2, 1000, 100000),
            'purchase_price' => $this->faker->randomFloat(2, 1000, 100000),
            'sale_date' => Carbon::now(),
            'purchase_date' => $this->faker->dateTimeThisYear(),
        ];
    }
}
