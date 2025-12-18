<?php

declare(strict_types=1);

namespace Modules\Esp32data\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Esp32Factory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Esp32data\Models\Esp32::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sensor' => $this->faker->sentence,
            'location' => $this->faker->sentence,
            'value1' => $this->faker->randomFloat(2, -1000, 1000),
            'value2' => $this->faker->randomFloat(2, -1000, 1000),
            'value3' => $this->faker->randomFloat(2, -1000, 1000),
        ];
    }
}
