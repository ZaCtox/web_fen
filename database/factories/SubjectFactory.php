<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'description' => $this->faker->paragraph(),
            'credits' => $this->faker->numberBetween(3, 8),
            'program_id' => Program::factory(),
            'trimester' => $this->faker->numberBetween(1, 4),
            'academic_year' => $this->faker->numberBetween(2020, 2025),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
