<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('setLocale')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
        });

        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::middleware('role:nastavnik')->group(function () {
            Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
            Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
            Route::get('/tasks/applications', [TaskController::class, 'applications'])->name('tasks.applications');
            Route::post('/tasks/applications/{application}/accept', [TaskController::class, 'accept'])->name('tasks.accept');
            Route::post('/tasks/applications/{application}/reject', [TaskController::class, 'reject'])->name('tasks.reject');
        });

        Route::middleware('role:student,admin')->group(function () {
            Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        });

        Route::middleware('role:student')->group(function () {
            Route::post('/tasks/{task}/apply', [TaskController::class, 'apply'])->name('tasks.apply');
            Route::delete('/tasks/{task}/withdraw', [TaskController::class, 'withdraw'])->name('tasks.withdraw');
        });

        Route::get('lang/{locale}', function ($locale) {
            $availableLanguages = ['hr', 'en'];

            if (in_array($locale, $availableLanguages)) {
                session(['locale' => $locale]);
            }

            return redirect()->back();
        })->name('locale.switch');
    });
});

require __DIR__.'/auth.php';
