<?php

namespace Database\Factories\Vehicles;

use App\Enums\Vehicles\VehiclesTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicles\VehiclesModels>
 */
class VehiclesModelsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand_id' => VehiclesBrandFactory::new(),
            'name' => $this->faker->streetName(),
            'type' => $this->faker->randomElement(VehiclesTypeEnum::class),
        ];
    }
}
