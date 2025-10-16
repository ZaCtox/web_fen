<?php

use App\Models\User;
use App\Models\Clase;
use App\Models\Course;
use App\Models\Period;
use App\Models\Room;
use App\Models\Magister;

beforeEach(function () {
    $this->user = User::factory()->create(['rol' => 'administrador']);
    $this->actingAs($this->user);
});

test('puede listar clases con paginación', function () {
    Clase::factory()->count(20)->create();

    $response = $this->getJson('/api/clases?per_page=10');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonCount(10, 'data.data');
});

test('puede filtrar clases por año de ingreso', function () {
    $period2024 = Period::factory()->create(['anio_ingreso' => 2024]);
    $period2023 = Period::factory()->create(['anio_ingreso' => 2023]);
    
    $course = Course::factory()->create();
    
    Clase::factory()->count(3)->create(['period_id' => $period2024->id, 'course_id' => $course->id]);
    Clase::factory()->count(2)->create(['period_id' => $period2023->id, 'course_id' => $course->id]);

    $response = $this->getJson('/api/clases?anio_ingreso=2024');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data.data');
});

test('puede filtrar clases por año del programa', function () {
    $period1 = Period::factory()->create(['anio' => 1, 'anio_ingreso' => 2024]);
    $period2 = Period::factory()->create(['anio' => 2, 'anio_ingreso' => 2024]);
    
    $course = Course::factory()->create();
    
    Clase::factory()->count(2)->create(['period_id' => $period1->id, 'course_id' => $course->id]);
    Clase::factory()->create(['period_id' => $period2->id, 'course_id' => $course->id]);

    $response = $this->getJson('/api/clases?anio=1');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data.data');
});

test('puede filtrar clases por trimestre', function () {
    $period1 = Period::factory()->create(['numero' => 1, 'anio_ingreso' => 2024]);
    $period2 = Period::factory()->create(['numero' => 2, 'anio_ingreso' => 2024]);
    
    $course = Course::factory()->create();
    
    Clase::factory()->count(3)->create(['period_id' => $period1->id, 'course_id' => $course->id]);
    Clase::factory()->create(['period_id' => $period2->id, 'course_id' => $course->id]);

    $response = $this->getJson('/api/clases?trimestre=1');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data.data');
});

test('puede filtrar clases por sala', function () {
    $room = Room::factory()->create();
    
    Clase::factory()->count(2)->create(['room_id' => $room->id]);
    Clase::factory()->count(3)->create();

    $response = $this->getJson("/api/clases?room_id={$room->id}");

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data.data');
});

test('puede filtrar clases por día', function () {
    Clase::factory()->count(3)->create(['dia' => 'Viernes']);
    Clase::factory()->count(2)->create(['dia' => 'Sábado']);

    $response = $this->getJson('/api/clases?dia=Viernes');

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data.data');
});

test('puede combinar múltiples filtros en clases', function () {
    $period = Period::factory()->create(['anio_ingreso' => 2024, 'numero' => 1]);
    $magister = Magister::factory()->create(['nombre' => 'Magíster en Gestión']);
    $course = Course::factory()->create(['magister_id' => $magister->id]);
    $room = Room::factory()->create();
    
    Clase::factory()->create([
        'period_id' => $period->id,
        'course_id' => $course->id,
        'room_id' => $room->id,
        'dia' => 'Viernes'
    ]);
    
    // Otras clases que no coinciden
    Clase::factory()->count(5)->create();

    $response = $this->getJson("/api/clases?anio_ingreso=2024&trimestre=1&magister=Gestión&dia=Viernes");

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.data');
});

test('puede obtener clases públicas sin autenticación', function () {
    $this->withoutAuthentication();
    
    Clase::factory()->count(5)->create();

    $response = $this->getJson('/api/public/clases');

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success');
});

test('puede crear una clase', function () {
    $course = Course::factory()->create();
    $period = Period::factory()->create();
    $room = Room::factory()->create();

    $response = $this->postJson('/api/clases', [
        'course_id' => $course->id,
        'tipo' => 'Presencial',
        'period_id' => $period->id,
        'room_id' => $room->id,
        'modality' => 'presencial',
        'dia' => 'Viernes',
        'hora_inicio' => '09:00',
        'hora_fin' => '13:00'
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('status', 'success');
});

test('valida que clases online requieran url zoom', function () {
    $course = Course::factory()->create();
    $period = Period::factory()->create();

    $response = $this->postJson('/api/clases', [
        'course_id' => $course->id,
        'tipo' => 'Online',
        'period_id' => $period->id,
        'modality' => 'online',
        'dia' => 'Viernes',
        'hora_inicio' => '09:00',
        'hora_fin' => '13:00'
        // url_zoom faltante
    ]);

    $response->assertStatus(422)
        ->assertJsonPath('status', 'error');
});

