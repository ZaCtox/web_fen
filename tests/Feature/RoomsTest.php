<?php

use App\Models\User;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Rooms Module', function () {
    
    beforeEach(function () {
        $this->admin = User::factory()->create([
            'rol' => 'director_administrativo',
        ]);
    });

    it('can access rooms index when authenticated', function () {
        $response = $this->actingAs($this->admin)->get('/rooms');
        
        $response->assertStatus(200);
        $response->assertViewIs('rooms.index');
    });

    it('can view create room form', function () {
        $response = $this->actingAs($this->admin)->get('/rooms/create');
        
        $response->assertStatus(200);
        $response->assertViewIs('rooms.form-wizard');
    });

    it('can create a new room', function () {
        $roomData = [
            'nombre' => 'Sala 101',
            'codigo' => 'S-101',
            'capacidad' => 30,
            'tipo' => 'aula',
            'piso' => 1,
            'edificio' => 'Edificio A',
            'estado' => 'disponible',
        ];

        $response = $this->actingAs($this->admin)->post('/rooms', $roomData);
        
        $response->assertRedirect('/rooms');
        $this->assertDatabaseHas('rooms', [
            'nombre' => 'Sala 101',
            'codigo' => 'S-101',
            'capacidad' => 30,
        ]);
    });

    it('validates required fields when creating room', function () {
        $response = $this->actingAs($this->admin)->post('/rooms', []);
        
        $response->assertSessionHasErrors(['nombre', 'codigo', 'capacidad', 'tipo']);
    });

    it('validates unique codigo when creating room', function () {
        Room::factory()->create(['codigo' => 'S-101']);

        $roomData = [
            'nombre' => 'Otra Sala',
            'codigo' => 'S-101', // Código duplicado
            'capacidad' => 25,
            'tipo' => 'aula',
        ];

        $response = $this->actingAs($this->admin)->post('/rooms', $roomData);
        
        $response->assertSessionHasErrors(['codigo']);
    });

    it('can update an existing room', function () {
        $room = Room::factory()->create([
            'nombre' => 'Sala Original',
            'capacidad' => 20,
        ]);

        $updateData = [
            'nombre' => 'Sala Actualizada',
            'codigo' => $room->codigo,
            'capacidad' => 35,
            'tipo' => $room->tipo,
        ];

        $response = $this->actingAs($this->admin)->put("/rooms/{$room->id}", $updateData);
        
        $response->assertRedirect('/rooms');
        $this->assertDatabaseHas('rooms', [
            'id' => $room->id,
            'nombre' => 'Sala Actualizada',
            'capacidad' => 35,
        ]);
    });

    it('can delete a room', function () {
        $room = Room::factory()->create();
        
        $response = $this->actingAs($this->admin)->delete("/rooms/{$room->id}");
        
        $response->assertRedirect('/rooms');
        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    });

    it('can filter rooms by type', function () {
        Room::factory()->create(['tipo' => 'aula', 'nombre' => 'Aula 1']);
        Room::factory()->create(['tipo' => 'laboratorio', 'nombre' => 'Lab 1']);

        $response = $this->actingAs($this->admin)->get('/rooms?tipo=aula');
        
        $response->assertSee('Aula 1');
        $response->assertDontSee('Lab 1');
    });

    it('can filter rooms by status', function () {
        Room::factory()->create(['estado' => 'disponible', 'nombre' => 'Disponible 1']);
        Room::factory()->create(['estado' => 'mantenimiento', 'nombre' => 'Mantenimiento 1']);

        $response = $this->actingAs($this->admin)->get('/rooms?estado=disponible');
        
        $response->assertSee('Disponible 1');
        $response->assertDontSee('Mantenimiento 1');
    });
});

