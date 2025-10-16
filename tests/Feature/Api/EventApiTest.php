<?php

use App\Models\User;
use App\Models\Event;
use App\Models\Clase;
use App\Models\Period;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Room;

beforeEach(function () {
    $this->user = User::factory()->create(['rol' => 'administrador']);
    $this->actingAs($this->user);
});

test('puede listar eventos autenticados', function () {
    Event::factory()->count(5)->create();

    $response = $this->getJson('/api/events?start=2024-01-01&end=2024-12-31');

    $response->assertStatus(200);
});

test('puede obtener eventos públicos sin autenticación', function () {
    $this->withoutAuthentication();
    
    Event::factory()->count(3)->create([
        'start_time' => now(),
        'end_time' => now()->addHours(2)
    ]);

    $response = $this->getJson('/api/public/events?start=' . now()->subDays(1)->format('Y-m-d') . '&end=' . now()->addDays(1)->format('Y-m-d'));

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success');
});

test('puede filtrar eventos públicos por año de ingreso', function () {
    $this->withoutAuthentication();
    
    $magister = Magister::factory()->create();
    $period2024 = Period::factory()->create([
        'anio_ingreso' => 2024,
        'fecha_inicio' => '2024-03-01',
        'fecha_fin' => '2024-06-30'
    ]);
    $course = Course::factory()->create(['magister_id' => $magister->id]);
    
    Clase::factory()->create([
        'period_id' => $period2024->id,
        'course_id' => $course->id,
        'dia' => 'Viernes',
        'hora_inicio' => '09:00:00',
        'hora_fin' => '13:00:00'
    ]);

    $response = $this->getJson('/api/public/events?anio_ingreso=2024&start=2024-03-01&end=2024-06-30');

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success');
});

test('puede filtrar eventos por magister', function () {
    $magister = Magister::factory()->create();
    
    Event::factory()->count(2)->create([
        'magister_id' => $magister->id,
        'start_time' => now(),
        'end_time' => now()->addHours(2)
    ]);
    
    Event::factory()->create([
        'start_time' => now(),
        'end_time' => now()->addHours(2)
    ]);

    $response = $this->getJson("/api/events?magister_id={$magister->id}&start=" . now()->subDays(1)->format('Y-m-d') . "&end=" . now()->addDays(1)->format('Y-m-d'));

    $response->assertStatus(200);
});

test('puede filtrar eventos por sala', function () {
    $room = Room::factory()->create();
    
    Event::factory()->count(2)->create([
        'room_id' => $room->id,
        'start_time' => now(),
        'end_time' => now()->addHours(2)
    ]);
    
    Event::factory()->create([
        'start_time' => now(),
        'end_time' => now()->addHours(2)
    ]);

    $response = $this->getJson("/api/events?room_id={$room->id}&start=" . now()->subDays(1)->format('Y-m-d') . "&end=" . now()->addDays(1)->format('Y-m-d'));

    $response->assertStatus(200);
});

test('puede crear un evento', function () {
    $magister = Magister::factory()->create();
    $room = Room::factory()->create();

    $response = $this->postJson('/api/events', [
        'title' => 'Evento de Prueba',
        'description' => 'Descripción del evento',
        'magister_id' => $magister->id,
        'room_id' => $room->id,
        'start_time' => now()->addDays(1)->format('Y-m-d H:i:s'),
        'end_time' => now()->addDays(1)->addHours(2)->format('Y-m-d H:i:s')
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('status', 'success');
});

test('puede actualizar un evento', function () {
    $event = Event::factory()->create();

    $response = $this->putJson("/api/events/{$event->id}", [
        'title' => 'Evento Actualizado',
        'start_time' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'end_time' => now()->addDays(2)->addHours(3)->format('Y-m-d H:i:s')
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success');
});

test('puede eliminar un evento', function () {
    $event = Event::factory()->create();

    $response = $this->deleteJson("/api/events/{$event->id}");

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success');
    
    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});

test('puede obtener calendario para app móvil', function () {
    Event::factory()->count(3)->create([
        'start_time' => now(),
        'end_time' => now()->addHours(2)
    ]);

    $response = $this->getJson('/api/calendario?start=' . now()->subDays(1)->format('Y-m-d') . '&end=' . now()->addDays(1)->format('Y-m-d'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'data',
            'meta'
        ]);
});

