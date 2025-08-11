<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicSite\PublicStaffController;
use App\Http\Controllers\PublicSite\PublicRoomController;
use App\Http\Controllers\GuestEventController;

// URLs públicas (solo lectura) 
Route::get('/staff-fen', [PublicStaffController::class, 'index'])->name('public.staff.index');
