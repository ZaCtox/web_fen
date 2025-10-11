<?php

use App\Models\User;
use App\Models\Incident;
use App\Models\Room;
use App\Models\Period;
use App\Models\Magister;
use App\Models\Course;
use App\Models\Clase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User Model', function () {
    it('has correct fillable fields', function () {
        $user = new User();
        
        expect($user->getFillable())->toContain('name', 'email', 'rol');
    });

    it('has incidents relationship', function () {
        $user = User::factory()->create();
        $incident = Incident::factory()->create(['user_id' => $user->id]);
        
        expect($user->incidents)->toHaveCount(1);
        expect($user->incidents->first()->id)->toBe($incident->id);
    });

    it('casts email_verified_at to datetime', function () {
        $user = User::factory()->create();
        
        expect($user->email_verified_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    });
});

describe('Incident Model', function () {
    it('belongs to user', function () {
        $user = User::factory()->create();
        $incident = Incident::factory()->create(['user_id' => $user->id]);
        
        expect($incident->user)->toBeInstanceOf(User::class);
        expect($incident->user->id)->toBe($user->id);
    });

    it('belongs to room', function () {
        $room = Room::factory()->create();
        $incident = Incident::factory()->create(['room_id' => $room->id]);
        
        expect($incident->room)->toBeInstanceOf(Room::class);
        expect($incident->room->id)->toBe($room->id);
    });

    it('has correct status values', function () {
        $statuses = ['pendiente', 'en_progreso', 'resuelta', 'cerrada'];
        
        foreach ($statuses as $status) {
            $incident = Incident::factory()->create(['estado' => $status]);
            expect($incident->estado)->toBe($status);
        }
    });

    it('has correct priority values', function () {
        $priorities = ['baja', 'media', 'alta', 'urgente'];
        
        foreach ($priorities as $priority) {
            $incident = Incident::factory()->create(['prioridad' => $priority]);
            expect($incident->prioridad)->toBe($priority);
        }
    });
});

describe('Room Model', function () {
    it('has incidents relationship', function () {
        $room = Room::factory()->create();
        $incident = Incident::factory()->create(['room_id' => $room->id]);
        
        expect($room->incidents)->toHaveCount(1);
        expect($room->incidents->first()->id)->toBe($incident->id);
    });

    it('has clases relationship', function () {
        $room = Room::factory()->create();
        $clase = Clase::factory()->create(['room_id' => $room->id]);
        
        expect($room->clases)->toHaveCount(1);
        expect($room->clases->first()->id)->toBe($clase->id);
    });

    it('validates unique codigo', function () {
        Room::factory()->create(['codigo' => 'S-101']);
        
        expect(fn() => Room::factory()->create(['codigo' => 'S-101']))
            ->toThrow(\Illuminate\Database\QueryException::class);
    });
});

describe('Period Model', function () {
    it('has courses relationship', function () {
        $period = Period::factory()->create();
        $course = Course::factory()->create(['period_id' => $period->id]);
        
        expect($period->courses)->toHaveCount(1);
        expect($period->courses->first()->id)->toBe($course->id);
    });

    it('casts fecha_inicio and fecha_fin to dates', function () {
        $period = Period::factory()->create();
        
        expect($period->fecha_inicio)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
        expect($period->fecha_fin)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    });

    it('casts activo to boolean', function () {
        $period = Period::factory()->create(['activo' => true]);
        
        expect($period->activo)->toBeTrue();
    });
});

describe('Magister Model', function () {
    it('has curricula relationship', function () {
        $magister = Magister::factory()->create();
        $curricula = \App\Models\Curricula::factory()->create(['magister_id' => $magister->id]);
        
        expect($magister->curricula)->toHaveCount(1);
        expect($magister->curricula->first()->id)->toBe($curricula->id);
    });

    it('has courses relationship', function () {
        $magister = Magister::factory()->create();
        $course = Course::factory()->create(['magister_id' => $magister->id]);
        
        expect($magister->courses)->toHaveCount(1);
        expect($magister->courses->first()->id)->toBe($course->id);
    });
});

describe('Course Model', function () {
    it('belongs to magister', function () {
        $magister = Magister::factory()->create();
        $course = Course::factory()->create(['magister_id' => $magister->id]);
        
        expect($course->magister)->toBeInstanceOf(Magister::class);
        expect($course->magister->id)->toBe($magister->id);
    });

    it('belongs to period', function () {
        $period = Period::factory()->create();
        $course = Course::factory()->create(['period_id' => $period->id]);
        
        expect($course->period)->toBeInstanceOf(Period::class);
        expect($course->period->id)->toBe($period->id);
    });

    it('has clases relationship', function () {
        $course = Course::factory()->create();
        $clase = Clase::factory()->create(['course_id' => $course->id]);
        
        expect($course->clases)->toHaveCount(1);
        expect($course->clases->first()->id)->toBe($clase->id);
    });
});

describe('Clase Model', function () {
    it('belongs to course', function () {
        $course = Course::factory()->create();
        $clase = Clase::factory()->create(['course_id' => $course->id]);
        
        expect($clase->course)->toBeInstanceOf(Course::class);
        expect($clase->course->id)->toBe($course->id);
    });

    it('belongs to room', function () {
        $room = Room::factory()->create();
        $clase = Clase::factory()->create(['room_id' => $room->id]);
        
        expect($clase->room)->toBeInstanceOf(Room::class);
        expect($clase->room->id)->toBe($room->id);
    });

    it('casts fecha_hora to datetime', function () {
        $clase = Clase::factory()->create();
        
        expect($clase->fecha_hora)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    });
});

