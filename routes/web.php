<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\TeamVerificationController;
use App\Http\Controllers\Api\HypeVoteController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/jadwal', [PublicController::class, 'schedule'])->name('public.schedule');
Route::post('/api/matches/{match}/hype', [HypeVoteController::class, 'store'])->name('api.matches.hype');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TeamController::class, 'index'])->name('dashboard');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::post('/teams/{team}/players', [PlayerController::class, 'store'])->name('players.store');
    Route::delete('/players/{player}', [PlayerController::class, 'destroy'])->name('players.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/teams', [TeamVerificationController::class, 'index'])->name('teams.index');
    Route::patch('/teams/{team}/status', [TeamVerificationController::class, 'updateStatus'])->name('teams.updateStatus');
    Route::delete('/teams/{team}', [TeamVerificationController::class, 'destroy'])->name('teams.destroy');
    Route::get('/matches', [MatchController::class, 'index'])->name('matches.index');
    Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');
    Route::patch('/matches/{match}/score', [MatchController::class, 'updateScore'])->name('matches.updateScore');
    Route::post('/matches/{match}/goals', [MatchController::class, 'recordGoal'])->name('matches.recordGoal');
    Route::patch('/matches/{match}', [MatchController::class, 'update'])->name('matches.update');
    Route::delete('/matches/{match}', [MatchController::class, 'destroy'])->name('matches.destroy');
});

require __DIR__.'/auth.php';
