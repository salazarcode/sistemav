<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+2 months');
        $endDate = clone $startDate;
        $endDate->modify('+' . rand(1, 5) . ' hours');

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->address(),
            'img' => $this->faker->imageUrl(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'institutions_id' => Institution::factory(),
            'user_id' => User::factory()
        ];
    }
} 