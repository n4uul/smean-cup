<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\Player;
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
        $topScorers = Player::topScorers(10)->get();

        return view('welcome', compact('teams', 'matches', 'topScorers'));
    }

    public function schedule(): View
    {
        $topScorers = Player::topScorers(10)->get();

        return view('public.schedule', compact('topScorers'));
    }
}
