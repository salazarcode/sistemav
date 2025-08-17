<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class PersonalDataFactory extends Factory
{
    public function definition(): array
    {
        // Generar una fecha de nacimiento aleatoria para personas entre 18 y 70 años
        $birthDate = Carbon::now()->subYears(rand(18, 70))->subDays(rand(0, 365));
        
        return [
            'name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'sex' => $this->faker->randomElement(['M', 'F']),
            'birth_date' => $birthDate->format('Y-m-d'),
            'dni' => $this->faker->unique()->numerify('########'),
            'type_dni' => $this->faker->randomElement(['DNI', 'Pasaporte', 'CE'])
        ];
    }
} 