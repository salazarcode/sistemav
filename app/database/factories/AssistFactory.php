<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AssistFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $endDate = clone $startDate;
        $endDate->modify('+' . rand(1, 8) . ' hours');

        return [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }
} 