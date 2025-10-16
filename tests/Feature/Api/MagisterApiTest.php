<?php

use App\Models\User;
use App\Models\Magister;
use App\Models\Course;
use App\Models\Period;

beforeEach(function () {
    $this->user = User::factory()->create(['rol' => 'administrador']);
    $this->actingAs($this->user);
});

test('puede listar magisters con paginación', function () {
    Magister::factory()->count(15)->create();

    $response = $this->getJson('/api/magisters?per_page=10');

    $response->assertStatus(200);
});

test('puede buscar magisters por nombre', function () {
    Magister::factory()->create(['nombre' => 'Magíster en Gestión de Empresas']);
    Magister::factory()->create(['nombre' => 'Magíster en Finanzas']);

    $response = $this->getJson('/api/magisters?q=Gestión');

    $response->assertStatus(200);
});

test('puede filtrar magisters por año de ingreso en contador de cursos', function () {
    $magister = Magister::factory()->create();
    $period2024 = Period::factory()->create(['anio_ingreso' => 2024]);
    $period2023 = Period::factory()->create(['anio_ingreso' => 2023]);
    
    // Crear cursos en diferentes años
    Course::factory()->count(3)->create([
        'magister_id' => $magister->id,
        'period_id' => $period2024->id
    ]);
    
    Course::factory()->count(2)->create([
        'magister_id' => $magister->id,
        'period_id' => $period2023->id
    ]);

    $response = $this->getJson("/api/magisters?anio_ingreso=2024");

    $response->assertStatus(200);
    
    // El contador debe mostrar solo 3 cursos
    $data = $response->json('data.data');
    expect($data[0]['courses_count'] ?? 0)->toBe(3);
});

test('puede ordenar magisters por nombre', function () {
    Magister::factory()->create(['nombre' => 'Magíster C']);
    Magister::factory()->create(['nombre' => 'Magíster A']);
    Magister::factory()->create(['nombre' => 'Magíster B']);

    $response = $this->getJson('/api/magisters?sort=nombre&direction=asc');

    $response->assertStatus(200);
});

test('puede obtener magisters públicos sin autenticación', function () {
    $this->withoutAuthentication();
    
    Magister::factory()->count(5)->create();

    $response = $this->getJson('/api/public/magisters');

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success');
});

test('puede obtener magisters públicos con contador de cursos', function () {
    $this->withoutAuthentication();
    
    $magister = Magister::factory()->create();
    Course::factory()->count(5)->create(['magister_id' => $magister->id]);

    $response = $this->getJson('/api/public/magisters-with-course-count');

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success');
});

test('puede crear un magister', function () {
    $response = $this->postJson('/api/magisters', [
        'nombre' => 'Nuevo Magíster de Prueba',
        'color' => '#FF5733',
        'encargado' => 'Dr. Juan Pérez',
        'telefono' => '+56912345678',
        'correo' => 'juan@example.com'
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('message', 'Programa creado correctamente.');
});

test('puede actualizar un magister', function () {
    $magister = Magister::factory()->create();

    $response = $this->putJson("/api/magisters/{$magister->id}", [
        'nombre' => 'Magíster Actualizado',
        'color' => '#00FF00'
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Programa actualizado.');
});

test('puede eliminar un magister y sus cursos', function () {
    $magister = Magister::factory()->create();
    Course::factory()->count(3)->create(['magister_id' => $magister->id]);

    $response = $this->deleteJson("/api/magisters/{$magister->id}");

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Programa y cursos asociados eliminados.');
    
    $this->assertDatabaseMissing('magisters', ['id' => $magister->id]);
    $this->assertDatabaseMissing('courses', ['magister_id' => $magister->id]);
});

