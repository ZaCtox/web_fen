<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Magister;
use App\Models\Period;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicEventFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+2 months');
        $endDate = clone $startDate;
        $endDate->modify('+2 hours');

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'room_id' => Room::factory(),
            'magister_id' => Magister::factory(),
            'period_id' => Period::factory(),
            'event_type' => $this->faker->randomElement(['clase', 'examen', 'reunion', 'evento_especial', 'otro']),
            'color' => $this->faker->hexColor(),
            'is_all_day' => false,
        ];
    }
}
