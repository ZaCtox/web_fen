<?php

use App\Models\User;
use App\Models\Course;
use App\Models\Magister;
use App\Models\Period;

beforeEach(function () {
    $this->user = User::factory()->create(['rol' => 'administrador']);
    $this->actingAs($this->user);
});

test('puede listar cursos autenticados con paginación', function () {
    Course::factory()->count(5)->create();

    $response = $this->getJson('/api/courses?per_page=3');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'data',
            'pagination' => [
                'current_page',
                'per_page',
                'total',
                'last_page'
            ]
        ]);
});

test('puede obtener cursos públicos sin autenticación', function () {
    $this->withoutAuthentication();
    
    Course::factory()->count(3)->create();

    $response = $this->getJson('/api/public/courses');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'data',
            'meta'
        ]);
});

test('puede filtrar cursos públicos por año de ingreso', function () {
    $this->withoutAuthentication();
    
    // Crear períodos con diferentes años de ingreso
    $period2024 = Period::factory()->create(['anio_ingreso' => 2024]);
    $period2023 = Period::factory()->create(['anio_ingreso' => 2023]);
    
    $magister = Magister::factory()->create();
    
    // Crear cursos
    Course::factory()->create([
        'magister_id' => $magister->id,
        'period_id' => $period2024->id
    ]);
    
    Course::factory()->count(2)->create([
        'magister_id' => $magister->id,
        'period_id' => $period2023->id
    ]);

    // Filtrar por 2024
    $response = $this->getJson('/api/public/courses?anio_ingreso=2024');

    $response->assertStatus(200)
        ->assertJsonPath('meta.anio_ingreso_filter', '2024')
        ->assertJsonCount(1, 'data');
});

test('puede obtener cursos de un magister específico con filtro de año', function () {
    $this->withoutAuthentication();
    
    $magister = Magister::factory()->create();
    $period2024 = Period::factory()->create(['anio_ingreso' => 2024]);
    
    Course::factory()->count(3)->create([
        'magister_id' => $magister->id,
        'period_id' => $period2024->id
    ]);

    $response = $this->getJson("/api/public/courses/magister/{$magister->id}?anio_ingreso=2024");

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('meta.magister_id', (string)$magister->id)
        ->assertJsonPath('meta.anio_ingreso_filter', '2024')
        ->assertJsonCount(3, 'data');
});

test('puede obtener años de ingreso disponibles', function () {
    $this->withoutAuthentication();
    
    Period::factory()->create(['anio_ingreso' => 2024]);
    Period::factory()->create(['anio_ingreso' => 2023]);
    Period::factory()->create(['anio_ingreso' => 2022]);

    $response = $this->getJson('/api/public/courses/years');

    $response->assertStatus(200)
        ->assertJsonStructure(['status', 'data'])
        ->assertJsonCount(3, 'data');
});

test('puede crear un curso autenticado', function () {
    $magister = Magister::factory()->create();
    $period = Period::factory()->create();

    $response = $this->postJson('/api/courses', [
        'nombre' => 'Nuevo Curso de Prueba',
        'magister_id' => $magister->id,
        'period_id' => $period->id
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('data.nombre', 'Nuevo Curso de Prueba');
});

test('puede actualizar un curso autenticado', function () {
    $course = Course::factory()->create();
    $newMagister = Magister::factory()->create();

    $response = $this->putJson("/api/courses/{$course->id}", [
        'nombre' => 'Curso Actualizado',
        'magister_id' => $newMagister->id,
        'period_id' => $course->period_id
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('message', 'Curso actualizado correctamente.');
});

test('puede eliminar un curso autenticado', function () {
    $course = Course::factory()->create();

    $response = $this->deleteJson("/api/courses/{$course->id}");

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success');
    
    $this->assertDatabaseMissing('courses', ['id' => $course->id]);
});

