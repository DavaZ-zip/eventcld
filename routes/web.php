<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\EventSessionController;
use App\Http\Controllers\SponsorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Events Routes
    Route::resource('events', EventController::class);
    Route::post('events/{event}/register', [EventController::class, 'register'])->name('events.register');
    
    // Speakers Routes
    Route::resource('speakers', SpeakerController::class);
    
    // Participants Routes
    Route::resource('participants', ParticipantController::class);
    
    // Sessions Routes
    Route::resource('sessions', EventSessionController::class);
    
    // Sponsors Routes
    Route::resource('sponsors', SponsorController::class);
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
