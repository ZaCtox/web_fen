<?php

use App\Models\User;
use App\Models\Period;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Periods Module', function () {
    
    beforeEach(function () {
        $this->admin = User::factory()->create([
            'rol' => 'director_administrativo',
        ]);
    });

    it('can access periods index when authenticated', function () {
        $response = $this->actingAs($this->admin)->get('/periods');
        
        $response->assertStatus(200);
        $response->assertViewIs('periods.index');
    });

    it('cannot access periods index when not authenticated', function () {
        $response = $this->get('/periods');
        
        $response->assertRedirect('/login');
    });

    it('can view create period form', function () {
        $response = $this->actingAs($this->admin)->get('/periods/create');
        
        $response->assertStatus(200);
        $response->assertViewIs('periods.form-wizard');
    });

    it('can create a new period', function () {
        $periodData = [
            'nombre' => 'Semestre 1 2024',
            'año' => 2024,
            'semestre' => 1,
            'fecha_inicio' => '2024-03-01',
            'fecha_fin' => '2024-07-31',
            'activo' => true,
        ];

        $response = $this->actingAs($this->admin)->post('/periods', $periodData);
        
        $response->assertRedirect('/periods');
        $this->assertDatabaseHas('periods', [
            'nombre' => 'Semestre 1 2024',
            'año' => 2024,
            'semestre' => 1,
        ]);
    });

    it('validates required fields when creating period', function () {
        $response = $this->actingAs($this->admin)->post('/periods', []);
        
        $response->assertSessionHasErrors(['nombre', 'año', 'semestre', 'fecha_inicio', 'fecha_fin']);
    });

    it('validates date logic (fecha_fin must be after fecha_inicio)', function () {
        $periodData = [
            'nombre' => 'Test Period',
            'año' => 2024,
            'semestre' => 1,
            'fecha_inicio' => '2024-07-31',
            'fecha_fin' => '2024-03-01', // Fecha fin antes de inicio
            'activo' => true,
        ];

        $response = $this->actingAs($this->admin)->post('/periods', $periodData);
        
        $response->assertSessionHasErrors(['fecha_fin']);
    });

    it('can update an existing period', function () {
        $period = Period::factory()->create([
            'nombre' => 'Original Period',
            'activo' => false,
        ]);

        $updateData = [
            'nombre' => 'Updated Period',
            'año' => $period->año,
            'semestre' => $period->semestre,
            'fecha_inicio' => $period->fecha_inicio,
            'fecha_fin' => $period->fecha_fin,
            'activo' => true,
        ];

        $response = $this->actingAs($this->admin)->put("/periods/{$period->id}", $updateData);
        
        $response->assertRedirect('/periods');
        $this->assertDatabaseHas('periods', [
            'id' => $period->id,
            'nombre' => 'Updated Period',
            'activo' => true,
        ]);
    });

    it('can delete a period', function () {
        $period = Period::factory()->create();
        
        $response = $this->actingAs($this->admin)->delete("/periods/{$period->id}");
        
        $response->assertRedirect('/periods');
        $this->assertDatabaseMissing('periods', ['id' => $period->id]);
    });

    it('only shows active periods by default', function () {
        Period::factory()->create(['activo' => true, 'nombre' => 'Active Period']);
        Period::factory()->create(['activo' => false, 'nombre' => 'Inactive Period']);

        $response = $this->actingAs($this->admin)->get('/periods');
        
        $response->assertSee('Active Period');
        $response->assertDontSee('Inactive Period');
    });
});

