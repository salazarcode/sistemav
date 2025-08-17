<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => $this->faker->randomElement([
                'Conferencia', 'Taller', 'Seminario', 'Curso', 
                'Charla', 'Exposición', 'Reunión', 'Ceremonia'
            ])
        ];
    }
} 