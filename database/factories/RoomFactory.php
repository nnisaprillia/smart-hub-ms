<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'capacity' => fake()->numberBetween(4, 20),
            'location' => fake()->randomElement(['Lantai 1', 'Lantai 2', 'Lantai 3', 'Gedung A']),
            'status' => fake()->randomElement(['available', 'maintenance']),
            'description' => fake()->sentence(),
        ];
    }
}
