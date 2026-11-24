<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\Team;
use Illuminate\Contracts\View\View;

class PublicController extends Controller
{
    public function index(): View
    {
        $teams = Team::where('status_verifikasi', 'approved')->orderBy('nama_tim')->get();
        $matches = MatchModel::with(['homeTeam', 'awayTeam'])
            ->orderBy('waktu_tanding')
            ->get();

        return view('welcome', compact('teams', 'matches'));
    }

    public function schedule(): View
    {
        return view('public.schedule');
    }
}
