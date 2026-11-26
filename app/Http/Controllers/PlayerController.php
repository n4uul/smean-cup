<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PlayerController extends Controller
{
    public function store(Request $request, Team $team): RedirectResponse
    {
        abort_if($team->user_id !== $request->user()->id, 403);

        $namaLengkap = $request->input('nama_lengkap');
        $nisn = $request->input('nisn');

        $request->merge([
            'nama_lengkap' => is_string($namaLengkap) ? trim($namaLengkap) : $namaLengkap,
            'nisn' => is_string($nisn) && trim($nisn) !== '' ? trim($nisn) : null,
            'roster_team_id' => $team->id,
        ]);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nomor_punggung' => [
                'required',
                'integer',
                'between:1,99',
                Rule::unique('players', 'nomor_punggung')->where('team_id', $team->id),
            ],
            'posisi' => ['required', 'string', Rule::in(Player::POSISI_LIST)],
            'nisn' => ['nullable', 'string', 'max:30'],
        ], [
            'nomor_punggung.unique' => 'Nomor punggung sudah digunakan oleh pemain lain di tim ini.',
        ]);

        DB::transaction(function () use ($team, $validated) {
            $lockedTeam = Team::query()
                ->lockForUpdate()
                ->findOrFail($team->getKey());

            if ($lockedTeam->players()->count() >= Team::MAX_PLAYERS) {
                throw ValidationException::withMessages([
                    'nama_lengkap' => 'Kuota roster pemain tim ini sudah penuh (Maksimal '.Team::MAX_PLAYERS.' pemain).',
                ]);
            }

            if ($lockedTeam->players()->where('nomor_punggung', $validated['nomor_punggung'])->exists()) {
                throw ValidationException::withMessages([
                    'nomor_punggung' => 'Nomor punggung sudah digunakan oleh pemain lain di tim ini.',
                ]);
            }

            $lockedTeam->players()->create([
                'nama_lengkap' => $validated['nama_lengkap'],
                'nomor_punggung' => (int) $validated['nomor_punggung'],
                'posisi' => $validated['posisi'],
                'nisn' => $validated['nisn'],
            ]);
        });

        return redirect()
            ->route('dashboard')
            ->with([
                'success' => "Pemain {$validated['nama_lengkap']} (#{$validated['nomor_punggung']}) berhasil ditambahkan ke roster {$team->nama_tim}.",
                'roster_team_id' => $team->id,
            ]);
    }

    public function destroy(Request $request, Player $player): RedirectResponse
    {
        $player->load('team');
        abort_if($player->team->user_id !== $request->user()->id, 403);

        $teamId = $player->team_id;
        $playerName = $player->nama_lengkap;
        $player->delete();

        return redirect()
            ->route('dashboard')
            ->with([
                'success' => "Pemain {$playerName} berhasil dihapus dari roster.",
                'roster_team_id' => $teamId,
            ]);
    }
}
