<?php

use App\Models\User;
use App\Models\Informe;
use App\Models\Magister;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create(['rol' => 'administrador']);
    $this->actingAs($this->user);
});

test('puede listar informes autenticados con filtros', function () {
    Informe::factory()->count(5)->create(['tipo' => 'academico']);

    $response = $this->getJson('/api/informes?tipo=academico&per_page=10');

    $response->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('puede filtrar informes por búsqueda de texto', function () {
    Informe::factory()->create(['nombre' => 'Informe Calendario Académico 2024']);
    Informe::factory()->create(['nombre' => 'Otro Documento']);

    $response = $this->getJson('/api/informes?search=calendario');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data.data');
});

test('puede obtener informes públicos sin autenticación', function () {
    $this->withoutAuthentication();
    
    Informe::factory()->count(3)->create();

    $response = $this->getJson('/api/public/informes');

    $response->assertStatus(200)
        ->assertJsonPath('status', 'success');
});

test('puede filtrar informes públicos por tipo', function () {
    $this->withoutAuthentication();
    
    Informe::factory()->count(2)->create(['tipo' => 'Informe Académico']);
    Informe::factory()->create(['tipo' => 'Reglamento']);

    $response = $this->getJson('/api/public/informes?tipo=Informe Académico');

    $response->assertStatus(200);
    
    // Verificar que la respuesta contiene data
    expect($response->json('data.data'))->toBeArray();
});

test('puede filtrar informes públicos por magister', function () {
    $this->withoutAuthentication();
    
    $magister = Magister::factory()->create();
    
    Informe::factory()->count(2)->create(['magister_id' => $magister->id]);
    Informe::factory()->create(['magister_id' => null]);

    $response = $this->getJson("/api/public/informes?magister_id={$magister->id}");

    $response->assertStatus(200);
});

test('puede filtrar informes públicos por usuario', function () {
    $this->withoutAuthentication();
    
    $usuario = User::factory()->create();
    
    Informe::factory()->count(2)->create(['user_id' => $usuario->id]);
    Informe::factory()->create(['user_id' => $this->user->id]);

    $response = $this->getJson("/api/public/informes?user_id={$usuario->id}");

    $response->assertStatus(200);
});

test('puede combinar múltiples filtros en informes públicos', function () {
    $this->withoutAuthentication();
    
    $magister = Magister::factory()->create();
    
    Informe::factory()->create([
        'nombre' => 'Acta Reunión 2024',
        'tipo' => 'Acta',
        'magister_id' => $magister->id
    ]);
    
    Informe::factory()->create([
        'nombre' => 'Otro Informe',
        'tipo' => 'Reglamento',
        'magister_id' => $magister->id
    ]);

    $response = $this->getJson("/api/public/informes?search=acta&tipo=Acta&magister_id={$magister->id}");

    $response->assertStatus(200);
});

test('puede crear un informe con archivo', function () {
    $magister = Magister::factory()->create();
    $file = UploadedFile::fake()->create('documento.pdf', 1024, 'application/pdf');

    $response = $this->postJson('/api/informes', [
        'nombre' => 'Informe de Prueba',
        'tipo' => 'Informe Académico',
        'archivo' => $file,
        'magister_id' => $magister->id
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true);
    
    Storage::disk('public')->assertExists('informes/' . $file->hashName());
});

test('puede obtener estadísticas de informes', function () {
    Informe::factory()->count(10)->create();

    $response = $this->getJson('/api/informes-statistics');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'total',
                'by_type',
                'recent',
                'this_month',
                'this_week'
            ]
        ]);
});

test('puede obtener recursos para crear informes', function () {
    Magister::factory()->count(3)->create();
    User::factory()->count(5)->create();

    $response = $this->getJson('/api/informes-resources');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'magisters',
                'users',
                'tipos'
            ]
        ]);
});

