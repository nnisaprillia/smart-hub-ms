<?php

namespace Database\Factories;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'category' => fake()->randomElement(['Kamera', 'Tripod', 'Microphone', 'Lighting', 'Mixer']),
            'stock' => fake()->numberBetween(1, 10),
            'condition' => fake()->randomElement(['good', 'damaged', 'lost']),
            'status' => fake()->randomElement(['available', 'borrowed']),
            'description' => fake()->sentence(),
            'image' => null,
        ];
    }
}
