<?php

use App\Http\Controllers\Api\LiveScoreController;
use Illuminate\Support\Facades\Route;

Route::get('/live-scores', [LiveScoreController::class, 'index']);
Route::get('/live-scores/active', [LiveScoreController::class, 'liveMatches']);
