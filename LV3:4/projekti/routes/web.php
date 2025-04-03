<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth'); // Ensures only logged-in users can see the welcome page

Route::get('/index', [ProjectController::class, 'index'])->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (only accessible to logged-in users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Project-related routes (only accessible to logged-in users)
Route::middleware(['auth'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');

    // Routes for adding and removing project members
    Route::post('/projects/{id}/add-member', [ProjectController::class, 'addMember'])->name('projects.addMember');
    Route::delete('/projects/{projectId}/remove-member/{userId}', [ProjectController::class, 'removeMember'])->name('projects.removeMember');

    // New routes for editing projects and updating tasks
    Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::patch('/projects/{id}/tasks', [ProjectController::class, 'updateTasks'])->name('projects.updateTasks');
});

require __DIR__.'/auth.php'; // Default Laravel authentication routes