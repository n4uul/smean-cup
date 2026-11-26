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
        $topScorersPutra = Player::topScorers(10, Team::KATEGORI_PUTRA)->get();
        $topScorersPutri = Player::topScorers(10, Team::KATEGORI_PUTRI)->get();

        return view('welcome', compact('teams', 'matches', 'topScorersPutra', 'topScorersPutri'));
    }

    public function schedule(): View
    {
        $topScorersPutra = Player::topScorers(10, Team::KATEGORI_PUTRA)->get();
        $topScorersPutri = Player::topScorers(10, Team::KATEGORI_PUTRI)->get();

        return view('public.schedule', compact('topScorersPutra', 'topScorersPutri'));
    }
}
