<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Studio Podcast',
                'Meeting Room A',
                'Coworking Space',
                'Studio Foto',
                'Studio Video'
            ]),

            'capacity' => fake()->numberBetween(5, 50),

            'location' => fake()->city(),

            'status' => fake()->randomElement([
                'available',
                'maintenance'
            ]),

            'description' => fake()->sentence(),
        ];
    }
}