<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\TeamVerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/jadwal', [PublicController::class, 'schedule'])->name('public.schedule');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TeamController::class, 'index'])->name('dashboard');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/teams', [TeamVerificationController::class, 'index'])->name('teams.index');
    Route::patch('/teams/{team}/status', [TeamVerificationController::class, 'updateStatus'])->name('teams.updateStatus');
    Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
    Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');
    Route::patch('/matches/{match}/score', [MatchController::class, 'updateScore'])->name('matches.updateScore');
    Route::patch('/matches/{match}', [MatchController::class, 'update'])->name('matches.update');
});

require __DIR__.'/auth.php';
