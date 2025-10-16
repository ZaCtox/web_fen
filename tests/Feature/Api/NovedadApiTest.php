<?php

use App\Models\User;
use App\Models\Novedad;
use App\Models\Magister;

beforeEach(function () {
    $this->user = User::factory()->create(['rol' => 'administrador']);
    $this->actingAs($this->user);
});

test('puede listar novedades autenticadas con filtros', function () {
    Novedad::factory()->count(5)->create([
        'tipo' => 'academica',
        'visible_publico' => true
    ]);

    $response = $this->getJson('/api/novedades?tipo=academica&per_page=10');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonCount(5, 'data.data');
});

test('puede filtrar novedades por búsqueda de texto', function () {
    Novedad::factory()->create(['titulo' => 'Evento Importante de Admisión']);
    Novedad::factory()->create(['titulo' => 'Otra Novedad']);

    $response = $this->getJson('/api/novedades?search=admisión');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.data');
});

test('puede filtrar novedades por estado activa', function () {
    // Novedad activa (sin expiración)
    Novedad::factory()->create([
        'titulo' => 'Activa',
        'fecha_expiracion' => null
    ]);
    
    // Novedad expirada
    Novedad::factory()->create([
        'titulo' => 'Expirada',
        'fecha_expiracion' => now()->subDays(5)
    ]);

    $response = $this->getJson('/api/novedades?estado=activa');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.data');
});

test('puede filtrar novedades por visibilidad pública', function () {
    Novedad::factory()->count(2)->create(['visible_publico' => true]);
    Novedad::factory()->count(3)->create(['visible_publico' => false]);

    $response = $this->getJson('/api/novedades?visibilidad=publica');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data.data');
});

test('puede obtener novedades activas públicas sin autenticación', function () {
    $this->withoutAuthentication();
    
    // Novedades activas
    Novedad::factory()->count(3)->create([
        'fecha_expiracion' => null
    ]);
    
    // Novedad expirada (no debe aparecer)
    Novedad::factory()->create([
        'fecha_expiracion' => now()->subDays(1)
    ]);

    $response = $this->getJson('/api/public/novedades');

    $response->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonCount(3, 'data');
});

test('puede filtrar novedades públicas por tipo y magister', function () {
    $this->withoutAuthentication();
    
    $magister = Magister::factory()->create();
    
    Novedad::factory()->count(2)->create([
        'tipo' => 'evento',
        'magister_id' => $magister->id,
        'fecha_expiracion' => null
    ]);
    
    Novedad::factory()->create([
        'tipo' => 'academica',
        'magister_id' => $magister->id,
        'fecha_expiracion' => null
    ]);

    $response = $this->getJson("/api/public/novedades?tipo=evento&magister_id={$magister->id}");

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

test('puede crear una novedad autenticada', function () {
    $magister = Magister::factory()->create();

    $response = $this->postJson('/api/novedades', [
        'titulo' => 'Nueva Novedad de Prueba',
        'contenido' => 'Contenido de la novedad de prueba',
        'tipo' => 'evento',
        'color' => 'blue',
        'icono' => 'info',
        'magister_id' => $magister->id,
        'es_urgente' => false
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.titulo', 'Nueva Novedad de Prueba');
});

test('puede obtener recursos para crear novedades', function () {
    Magister::factory()->count(3)->create();

    $response = $this->getJson('/api/novedades-resources');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'magisters',
                'tipos',
                'colores',
                'iconos'
            ]
        ]);
});

test('puede obtener estadísticas de novedades', function () {
    Novedad::factory()->count(10)->create();

    $response = $this->getJson('/api/novedades-statistics');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'total',
                'active',
                'urgent',
                'by_type',
                'by_color',
                'recent',
                'this_month',
                'this_week'
            ]
        ]);
});

