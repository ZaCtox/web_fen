<?php

use App\Models\User;
use App\Models\Magister;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Mallas Curriculares Module', function () {
    
    beforeEach(function () {
        $this->admin = User::factory()->create([
            'rol' => 'director_administrativo',
        ]);
    });

    it('can access mallas-curriculares index when authenticated', function () {
        $response = $this->actingAs($this->admin)->get('/mallas-curriculares');
        
        $response->assertStatus(200);
        $response->assertViewIs('mallas-curriculares.index');
    });

    it('can view create malla form', function () {
        $response = $this->actingAs($this->admin)->get('/mallas-curriculares/create');
        
        $response->assertStatus(200);
        $response->assertViewIs('mallas-curriculares.form-wizard');
    });

    it('can create a new malla curricular', function () {
        $magister = Magister::factory()->create();

        $mallaData = [
            'magister_id' => $magister->id,
            'nombre' => 'Malla 2024',
            'codigo' => 'MC-2024-01',
            'año_inicio' => 2024,
            'año_fin' => 2026,
            'descripcion' => 'Malla curricular actualizada',
            'estado' => 'activo',
        ];

        $response = $this->actingAs($this->admin)->post('/mallas-curriculares', $mallaData);
        
        $response->assertRedirect('/mallas-curriculares');
        $this->assertDatabaseHas('curricula', [
            'nombre' => 'Malla 2024',
            'codigo' => 'MC-2024-01',
        ]);
    });

    it('validates required fields when creating malla', function () {
        $response = $this->actingAs($this->admin)->post('/mallas-curriculares', []);
        
        $response->assertSessionHasErrors(['magister_id', 'nombre', 'codigo', 'año_inicio', 'año_fin']);
    });

    it('validates año_fin must be after año_inicio', function () {
        $magister = Magister::factory()->create();

        $mallaData = [
            'magister_id' => $magister->id,
            'nombre' => 'Malla Test',
            'codigo' => 'MC-TEST',
            'año_inicio' => 2026,
            'año_fin' => 2024, // Año fin antes que inicio
            'estado' => 'activo',
        ];

        $response = $this->actingAs($this->admin)->post('/mallas-curriculares', $mallaData);
        
        $response->assertSessionHasErrors(['año_fin']);
    });

    it('can update an existing malla', function () {
        $magister = Magister::factory()->create();
        $malla = \App\Models\Curricula::factory()->create([
            'magister_id' => $magister->id,
            'estado' => 'borrador',
        ]);

        $updateData = [
            'magister_id' => $magister->id,
            'nombre' => $malla->nombre,
            'codigo' => $malla->codigo,
            'año_inicio' => $malla->año_inicio,
            'año_fin' => $malla->año_fin,
            'estado' => 'activo',
        ];

        $response = $this->actingAs($this->admin)->put("/mallas-curriculares/{$malla->id}", $updateData);
        
        $response->assertRedirect('/mallas-curriculares');
        $this->assertDatabaseHas('curricula', [
            'id' => $malla->id,
            'estado' => 'activo',
        ]);
    });

    it('can delete a malla curricular', function () {
        $magister = Magister::factory()->create();
        $malla = \App\Models\Curricula::factory()->create([
            'magister_id' => $magister->id,
        ]);
        
        $response = $this->actingAs($this->admin)->delete("/mallas-curriculares/{$malla->id}");
        
        $response->assertRedirect('/mallas-curriculares');
        $this->assertDatabaseMissing('curricula', ['id' => $malla->id]);
    });
});

