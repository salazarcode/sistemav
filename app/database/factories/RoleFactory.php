<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Admin', 'Usuario', 'Supervisor', 'Coordinador', 'Invitado'])
        ];
    }
} 