<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Sony A7 III',
                'Canon EOS R',
                'Tripod DJI',
                'Lighting LED',
                'Microphone Rode',
                'Speaker JBL'
            ]),

            'category' => fake()->randomElement([
                'Kamera',
                'Tripod',
                'Lighting Studio',
                'Mikrofon',
                'Speaker'
            ]),

            'stock' => fake()->numberBetween(1, 20),

            'condition' => 'good',

            'status' => 'available',

            'description' => fake()->sentence(),

            'image' => null,
        ];
    }
}