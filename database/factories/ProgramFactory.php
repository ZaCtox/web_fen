<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'description' => $this->faker->paragraph(),
            'duration_trimesters' => $this->faker->numberBetween(6, 12),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
