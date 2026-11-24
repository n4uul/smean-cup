<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatchController extends Controller
{
    public function index()
    {
        $matches = MatchModel::with(['homeTeam', 'awayTeam'])->latest()->get();
        $teams = Team::where('status_verifikasi', 'approved')->get();

        return view('admin.matches.index', compact('matches', 'teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_home_id' => ['required', 'integer', 'different:team_away_id', Rule::exists('teams', 'id')->where('status_verifikasi', 'approved')],
            'team_away_id' => ['required', 'integer', Rule::exists('teams', 'id')->where('status_verifikasi', 'approved')],
            'waktu_tanding' => ['required', 'date'],
            'lapangan' => ['required', 'string', 'max:255'],
            'babak' => ['required', 'string', 'max:255'],
        ]);

        MatchModel::create([
            ...$validated,
            'status_match' => 'upcoming',
        ]);

        return redirect()->back()->with('success', 'Jadwal pertandingan berhasil ditambahkan!');
    }

    public function update(Request $request, MatchModel $match)
    {
        $validated = $request->validate([
            'skor_home' => ['required', 'integer', 'min:0'],
            'skor_away' => ['required', 'integer', 'min:0'],
            'status_match' => ['required', Rule::in(['upcoming', 'live', 'finished'])],
        ]);

        $match->update($validated);

        return redirect()->back()->with('success', 'Skor dan status pertandingan berhasil diperbarui!');
    }

    public function updateScore(Request $request, MatchModel $match)
    {
        $validated = $request->validate([
            'side' => ['required', Rule::in(['home', 'away'])],
            'action' => ['required', Rule::in(['increment', 'decrement'])],
        ]);

        $scoreColumn = $validated['side'] === 'home' ? 'skor_home' : 'skor_away';

        if ($validated['action'] === 'increment') {
            $match->increment($scoreColumn);
        } else {
            MatchModel::query()
                ->whereKey($match->getKey())
                ->where($scoreColumn, '>', 0)
                ->decrement($scoreColumn);
        }

        $match->refresh();

        return response()->json([
            'success' => true,
            'data' => [
                'skor_home' => $match->skor_home,
                'skor_away' => $match->skor_away,
            ],
        ]);
    }
}
