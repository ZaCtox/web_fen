<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AsignarAnioIngresoSeeder extends Seeder
{
    /**
     * Este seeder ya no es necesario porque los usuarios NO tienen
     * un año de ingreso asociado. El año de ingreso solo se usa
     * para los períodos académicos.
     * 
     * Los usuarios simplemente ven todos los períodos disponibles
     * y pueden filtrar por año de ingreso usando el selector.
     */
    public function run(): void
    {
        $this->command->info("✅ Los usuarios no requieren año de ingreso. El año de ingreso solo se usa para los períodos académicos.");
    }
}

