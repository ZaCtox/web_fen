<?php

use App\Models\User;
use App\Models\Room;
use App\Models\Period;
use App\Models\Magister;
use App\Models\Course;
use App\Models\Incident;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Platform Integration Tests', function () {
    
    beforeEach(function () {
        $this->admin = User::factory()->create([
            'rol' => 'director_administrativo',
            'name' => 'Admin User',
            'email' => 'admin@fen.cl',
        ]);
    });

    it('can complete full user workflow', function () {
        // Login
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
        
        // Create new user
        $userData = [
            'name' => 'New Staff Member',
            'email' => 'staff@fen.cl',
            'rol' => 'docente',
        ];
        $response = $this->actingAs($this->admin)->post('/register', $userData);
        $response->assertRedirect('/usuarios');
        
        // Verify user was created
        $this->assertDatabaseHas('users', ['email' => 'staff@fen.cl']);
    });

    it('can complete full room and incident workflow', function () {
        // Create room
        $roomData = [
            'nombre' => 'Sala Principal',
            'codigo' => 'SP-001',
            'capacidad' => 40,
            'tipo' => 'aula',
            'estado' => 'disponible',
        ];
        $response = $this->actingAs($this->admin)->post('/rooms', $roomData);
        $response->assertRedirect('/rooms');
        
        $room = Room::where('codigo', 'SP-001')->first();
        
        // Report incident in the room
        $incidentData = [
            'titulo' => 'Problema de Iluminación',
            'descripcion' => 'Las luces no funcionan correctamente',
            'tipo' => 'infraestructura',
            'prioridad' => 'alta',
            'room_id' => $room->id,
            'estado' => 'pendiente',
        ];
        $response = $this->actingAs($this->admin)->post('/incidencias', $incidentData);
        $response->assertRedirect('/incidencias');
        
        // Verify incident was created and linked to room
        $incident = Incident::where('titulo', 'Problema de Iluminación')->first();
        expect($incident->room_id)->toBe($room->id);
        expect($incident->user_id)->toBe($this->admin->id);
    });

    it('can complete full academic period workflow', function () {
        // Create magister
        $magister = Magister::factory()->create([
            'nombre' => 'Magíster en Administración',
            'codigo' => 'MAD',
        ]);
        
        // Create period
        $periodData = [
            'nombre' => 'Primer Semestre 2024',
            'año' => 2024,
            'semestre' => 1,
            'fecha_inicio' => '2024-03-01',
            'fecha_fin' => '2024-07-31',
            'activo' => true,
        ];
        $response = $this->actingAs($this->admin)->post('/periods', $periodData);
        $response->assertRedirect('/periods');
        
        $period = Period::where('nombre', 'Primer Semestre 2024')->first();
        
        // Create course for that period
        $courseData = [
            'nombre' => 'Gestión Estratégica',
            'codigo' => 'GES-101',
            'magister_id' => $magister->id,
            'period_id' => $period->id,
            'creditos' => 4,
        ];
        $response = $this->actingAs($this->admin)->post('/courses', $courseData);
        $response->assertRedirect('/courses');
        
        // Verify relationships
        $course = Course::where('codigo', 'GES-101')->first();
        expect($course->period_id)->toBe($period->id);
        expect($course->magister_id)->toBe($magister->id);
    });

    it('verifies all main modules are accessible', function () {
        $modules = [
            '/dashboard',
            '/usuarios',
            '/rooms',
            '/periods',
            '/courses',
            '/clases',
            '/incidencias',
            '/mallas-curriculares',
            '/magisters',
            '/emergencies',
            '/novedades',
            '/staff',
        ];

        foreach ($modules as $module) {
            $response = $this->actingAs($this->admin)->get($module);
            expect($response->status())->toBeLessThan(400, "Module {$module} should be accessible");
        }
    });

    it('verifies all wizards are accessible', function () {
        $wizards = [
            '/register',
            '/rooms/create',
            '/periods/create',
            '/courses/create',
            '/incidencias/create',
            '/mallas-curriculares/create',
            '/emergencies/create',
            '/novedades/create',
            '/staff/create',
        ];

        foreach ($wizards as $wizard) {
            $response = $this->actingAs($this->admin)->get($wizard);
            expect($response->status())->toBe(200, "Wizard {$wizard} should be accessible");
        }
    });

    it('verifies authentication is required for protected routes', function () {
        $protectedRoutes = [
            '/dashboard',
            '/usuarios',
            '/rooms',
            '/periods',
            '/incidencias',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    });

    it('can search and filter across modules', function () {
        // Create test data
        Room::factory()->create(['nombre' => 'Sala Test', 'estado' => 'disponible']);
        Room::factory()->create(['nombre' => 'Sala Mantenimiento', 'estado' => 'mantenimiento']);
        
        // Search by name
        $response = $this->actingAs($this->admin)->get('/rooms?search=Test');
        $response->assertSee('Sala Test');
        
        // Filter by status
        $response = $this->actingAs($this->admin)->get('/rooms?estado=disponible');
        $response->assertSee('Sala Test');
        $response->assertDontSee('Sala Mantenimiento');
    });

    it('validates data integrity across related models', function () {
        $magister = Magister::factory()->create();
        $period = Period::factory()->create();
        $course = Course::factory()->create([
            'magister_id' => $magister->id,
            'period_id' => $period->id,
        ]);
        
        // Verify cascade relationships
        expect($magister->courses)->toHaveCount(1);
        expect($period->courses)->toHaveCount(1);
        expect($course->magister->id)->toBe($magister->id);
        expect($course->period->id)->toBe($period->id);
    });
});

