<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crea usuario de prueba
/*         User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
 */
        //$this->call(TrimestreSeeder::class);
        //$this->call(CourseSeeder::class);
        //$this->call(PeriodSeeder::class);
        //$this->call(MagistersTableSeeder::class);
        //$this->call(CoursesTableSeeder::class);
        $this->call([
            PeriodSeeder::class,
            UsersTableSeeder::class,
            MagistersTableSeeder::class,
            CoursesTableSeeder::class,
            RoomsTableSeeder::class,
            IncidentsTableSeeder::class,
        ]);

    }
}
