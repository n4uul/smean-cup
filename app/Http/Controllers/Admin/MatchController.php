<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MatchController extends Controller
{
    public function index()
    {
        $matches = MatchModel::with([
            'homeTeam.players' => fn ($query) => $query->orderBy('nomor_punggung'),
            'awayTeam.players' => fn ($query) => $query->orderBy('nomor_punggung'),
        ])->latest()->get();
        $teams = Team::where('status_verifikasi', 'approved')
            ->orderBy('kategori')
            ->orderBy('nama_tim')
            ->get();

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

        $homeTeam = Team::findOrFail($validated['team_home_id']);
        $awayTeam = Team::findOrFail($validated['team_away_id']);

        if ($homeTeam->kategori !== $awayTeam->kategori) {
            throw ValidationException::withMessages([
                'team_away_id' => 'Pertandingan hanya boleh mempertemukan tim dengan kategori yang sama (Putra vs Putra atau Putri vs Putri).',
            ]);
        }

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

    public function recordGoal(Request $request, MatchModel $match)
    {
        $validated = $request->validate([
            'team_side' => ['required', Rule::in(['home', 'away'])],
            'player_id' => ['required', 'integer', 'exists:players,id'],
            'increment_score' => ['nullable', 'boolean'],
        ]);

        $expectedTeamId = $validated['team_side'] === 'home'
            ? $match->team_home_id
            : $match->team_away_id;

        $player = Player::query()
            ->whereKey($validated['player_id'])
            ->where('team_id', $expectedTeamId)
            ->first();

        if (! $player) {
            throw ValidationException::withMessages([
                'player_id' => 'Pemain yang dipilih tidak terdaftar pada tim tersebut.',
            ]);
        }

        DB::transaction(function () use ($match, $player, $validated) {
            $player->increment('jumlah_gol');

            if (! empty($validated['increment_score'])) {
                $scoreColumn = $validated['team_side'] === 'home' ? 'skor_home' : 'skor_away';
                $match->increment($scoreColumn);
            }
        });

        $player->refresh();
        $match->refresh();

        $message = "Gol untuk {$player->nama_lengkap} (#{$player->nomor_punggung}) berhasil dicatat (Total: {$player->jumlah_gol} gol).";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'player_id' => $player->id,
                    'jumlah_gol' => $player->jumlah_gol,
                    'skor_home' => $match->skor_home,
                    'skor_away' => $match->skor_away,
                ],
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy(MatchModel $match)
    {
        $match->delete();

        return redirect()->back()->with('success', 'Jadwal pertandingan berhasil dihapus!');
    }
}
