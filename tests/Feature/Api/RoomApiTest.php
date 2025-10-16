<?php

use App\Models\User;
use App\Models\Room;

beforeEach(function () {
    $this->user = User::factory()->create(['rol' => 'administrador']);
    $this->actingAs($this->user);
});

test('puede listar salas con paginación', function () {
    Room::factory()->count(15)->create();

    $response = $this->getJson('/api/rooms?per_page=10');

    $response->assertStatus(200)
        ->assertJsonStructure(['data', 'links', 'meta']);
});

test('puede filtrar salas por búsqueda de nombre', function () {
    Room::factory()->create(['name' => 'Sala A301']);
    Room::factory()->create(['name' => 'Sala B102']);
    Room::factory()->create(['name' => 'Auditorio']);

    $response = $this->getJson('/api/rooms?search=A301');

    $response->assertStatus(200);
    
    $data = $response->json('data');
    expect($data)->toBeArray();
});

test('puede filtrar salas por ubicación', function () {
    Room::factory()->create(['location' => 'Edificio Principal, Piso 3']);
    Room::factory()->create(['location' => 'Edificio Anexo']);

    $response = $this->getJson('/api/rooms?ubicacion=Principal');

    $response->assertStatus(200);
});

test('puede filtrar salas por capacidad mínima', function () {
    Room::factory()->create(['capacity' => 50]);
    Room::factory()->create(['capacity' => 30]);
    Room::factory()->create(['capacity' => 20]);

    $response = $this->getJson('/api/rooms?capacidad=40');

    $response->assertStatus(200);
});

test('puede ordenar salas por nombre ascendente', function () {
    Room::factory()->create(['name' => 'Sala C']);
    Room::factory()->create(['name' => 'Sala A']);
    Room::factory()->create(['name' => 'Sala B']);

    $response = $this->getJson('/api/rooms?sort=name&direction=asc');

    $response->assertStatus(200);
});

test('puede ordenar salas por capacidad descendente', function () {
    Room::factory()->create(['name' => 'Sala 1', 'capacity' => 30]);
    Room::factory()->create(['name' => 'Sala 2', 'capacity' => 50]);
    Room::factory()->create(['name' => 'Sala 3', 'capacity' => 20]);

    $response = $this->getJson('/api/rooms?sort=capacity&direction=desc');

    $response->assertStatus(200);
});

test('puede obtener salas públicas sin autenticación', function () {
    $this->withoutAuthentication();
    
    Room::factory()->count(5)->create();

    $response = $this->getJson('/api/public/rooms');

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success')
        ->assertJsonStructure([
            'status',
            'data',
            'meta' => ['total', 'public_view']
        ]);
});

test('puede crear una sala', function () {
    $response = $this->postJson('/api/rooms', [
        'name' => 'Sala Nueva',
        'capacity' => 40,
        'location' => 'Edificio Principal',
        'calefaccion' => true,
        'energia_electrica' => true
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('message', 'Sala creada correctamente');
});

test('puede actualizar una sala', function () {
    $room = Room::factory()->create();

    $response = $this->putJson("/api/rooms/{$room->id}", [
        'name' => 'Sala Actualizada',
        'capacity' => 50,
        'location' => 'Nueva Ubicación',
        'calefaccion' => true
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Sala actualizada correctamente');
});

test('puede eliminar una sala', function () {
    $room = Room::factory()->create();

    $response = $this->deleteJson("/api/rooms/{$room->id}");

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Sala eliminada correctamente');
    
    $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
});

