<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Usuarios Module', function () {
    
    beforeEach(function () {
        // Crear un usuario administrador para las pruebas
        $this->admin = User::factory()->create([
            'rol' => 'director_administrativo',
            'email' => 'admin@test.com',
        ]);
    });

    it('can access usuarios index when authenticated', function () {
        $response = $this->actingAs($this->admin)->get('/usuarios');
        
        $response->assertStatus(200);
        $response->assertViewIs('usuarios.index');
    });

    it('cannot access usuarios index when not authenticated', function () {
        $response = $this->get('/usuarios');
        
        $response->assertRedirect('/login');
    });

    it('can view create usuario form', function () {
        $response = $this->actingAs($this->admin)->get('/register');
        
        $response->assertStatus(200);
        $response->assertViewIs('usuarios.form-wizard');
    });

    it('can create a new usuario', function () {
        $userData = [
            'name' => 'Test User',
            'email' => 'testuser@test.com',
            'rol' => 'docente',
        ];

        $response = $this->actingAs($this->admin)->post('/register', $userData);
        
        $response->assertRedirect('/usuarios');
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@test.com',
            'rol' => 'docente',
        ]);
    });

    it('validates required fields when creating usuario', function () {
        $response = $this->actingAs($this->admin)->post('/register', []);
        
        $response->assertSessionHasErrors(['name', 'email', 'rol']);
    });

    it('validates email format when creating usuario', function () {
        $userData = [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'rol' => 'docente',
        ];

        $response = $this->actingAs($this->admin)->post('/register', $userData);
        
        $response->assertSessionHasErrors(['email']);
    });

    it('can view edit usuario form', function () {
        $user = User::factory()->create();
        
        $response = $this->actingAs($this->admin)->get("/usuarios/{$user->id}/edit");
        
        $response->assertStatus(200);
        $response->assertViewIs('usuarios.form-wizard');
        $response->assertViewHas('usuario', $user);
    });

    it('can update an existing usuario', function () {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@test.com',
            'rol' => 'docente',
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'rol' => 'director_programa',
        ];

        $response = $this->actingAs($this->admin)->put("/usuarios/{$user->id}", $updateData);
        
        $response->assertRedirect('/usuarios');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'rol' => 'director_programa',
        ]);
    });

    it('can delete a usuario', function () {
        $user = User::factory()->create();
        
        $response = $this->actingAs($this->admin)->delete("/usuarios/{$user->id}");
        
        $response->assertRedirect('/usuarios');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    });

    it('prevents duplicate email addresses', function () {
        User::factory()->create(['email' => 'duplicate@test.com']);

        $userData = [
            'name' => 'Another User',
            'email' => 'duplicate@test.com',
            'rol' => 'docente',
        ];

        $response = $this->actingAs($this->admin)->post('/register', $userData);
        
        $response->assertSessionHasErrors(['email']);
    });
});

