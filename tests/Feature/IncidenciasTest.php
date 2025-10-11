<?php

use App\Models\User;
use App\Models\Incident;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

describe('Incidencias Module', function () {
    
    beforeEach(function () {
        $this->admin = User::factory()->create([
            'rol' => 'director_administrativo',
        ]);
        
        $this->room = Room::factory()->create();
        Storage::fake('public');
    });

    it('can access incidencias index when authenticated', function () {
        $response = $this->actingAs($this->admin)->get('/incidencias');
        
        $response->assertStatus(200);
        $response->assertViewIs('incidencias.index');
    });

    it('cannot access incidencias index when not authenticated', function () {
        $response = $this->get('/incidencias');
        
        $response->assertRedirect('/login');
    });

    it('can view create incidencia form', function () {
        $response = $this->actingAs($this->admin)->get('/incidencias/create');
        
        $response->assertStatus(200);
        $response->assertViewIs('incidencias.form-wizard');
    });

    it('can create a new incidencia', function () {
        $incidentData = [
            'titulo' => 'Problema de Red',
            'descripcion' => 'La red WiFi no funciona en la sala',
            'tipo' => 'infraestructura',
            'prioridad' => 'alta',
            'room_id' => $this->room->id,
            'ubicacion_especifica' => 'Sala 101',
            'estado' => 'pendiente',
        ];

        $response = $this->actingAs($this->admin)->post('/incidencias', $incidentData);
        
        $response->assertRedirect('/incidencias');
        $this->assertDatabaseHas('incidents', [
            'titulo' => 'Problema de Red',
            'tipo' => 'infraestructura',
            'prioridad' => 'alta',
        ]);
    });

    it('validates required fields when creating incidencia', function () {
        $response = $this->actingAs($this->admin)->post('/incidencias', []);
        
        $response->assertSessionHasErrors(['titulo', 'descripcion', 'tipo', 'prioridad']);
    });

    it('can upload evidence when creating incidencia', function () {
        $file = UploadedFile::fake()->image('evidence.jpg');

        $incidentData = [
            'titulo' => 'Incidencia con Evidencia',
            'descripcion' => 'Descripción de la incidencia',
            'tipo' => 'infraestructura',
            'prioridad' => 'media',
            'room_id' => $this->room->id,
            'evidencia' => $file,
        ];

        $response = $this->actingAs($this->admin)->post('/incidencias', $incidentData);
        
        $response->assertRedirect('/incidencias');
        
        $incident = Incident::where('titulo', 'Incidencia con Evidencia')->first();
        expect($incident->evidencia)->not->toBeNull();
        Storage::disk('public')->assertExists($incident->evidencia);
    });

    it('can update incidencia status', function () {
        $incident = Incident::factory()->create([
            'estado' => 'pendiente',
            'user_id' => $this->admin->id,
        ]);

        $updateData = [
            'titulo' => $incident->titulo,
            'descripcion' => $incident->descripcion,
            'tipo' => $incident->tipo,
            'prioridad' => $incident->prioridad,
            'estado' => 'en_progreso',
        ];

        $response = $this->actingAs($this->admin)->put("/incidencias/{$incident->id}", $updateData);
        
        $response->assertRedirect('/incidencias');
        $this->assertDatabaseHas('incidents', [
            'id' => $incident->id,
            'estado' => 'en_progreso',
        ]);
    });

    it('can delete an incidencia', function () {
        $incident = Incident::factory()->create([
            'user_id' => $this->admin->id,
        ]);
        
        $response = $this->actingAs($this->admin)->delete("/incidencias/{$incident->id}");
        
        $response->assertRedirect('/incidencias');
        $this->assertDatabaseMissing('incidents', ['id' => $incident->id]);
    });

    it('can filter incidencias by status', function () {
        Incident::factory()->create(['estado' => 'pendiente', 'titulo' => 'Pendiente 1', 'user_id' => $this->admin->id]);
        Incident::factory()->create(['estado' => 'resuelta', 'titulo' => 'Resuelta 1', 'user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get('/incidencias?estado=pendiente');
        
        $response->assertSee('Pendiente 1');
        $response->assertDontSee('Resuelta 1');
    });

    it('can filter incidencias by priority', function () {
        Incident::factory()->create(['prioridad' => 'alta', 'titulo' => 'Alta Prioridad', 'user_id' => $this->admin->id]);
        Incident::factory()->create(['prioridad' => 'baja', 'titulo' => 'Baja Prioridad', 'user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get('/incidencias?prioridad=alta');
        
        $response->assertSee('Alta Prioridad');
        $response->assertDontSee('Baja Prioridad');
    });
});

