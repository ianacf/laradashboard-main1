<?php

declare(strict_types=1);

namespace Modules\DeviceManager\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\DeviceManager\Models\Device::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'device_name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'api_key' => $this->faker->sentence,            
            'status' => $this->faker->randomElement(['pending', 'enable', 'disable']),
            'assigned_to' => $this->faker->numberBetween(1, 10), // Assuming user IDs from 1 to 10
            'created_by' => $this->faker->numberBetween(1, 10), // Assuming user IDs from 1 to 10
        ];
    }
}
