<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeamVerificationController extends Controller
{
    // Menampilkan daftar semua tim yang mendaftar di dashboard admin
    public function index()
    {
        $teams = Team::with('user')->latest()->get();

        return view('admin.teams.index', compact('teams'));
    }

    // Mengubah status verifikasi tim (approved / rejected)
    public function updateStatus(Request $request, Team $team)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:pending,approved,rejected',
        ]);

        $team->update([
            'status_verifikasi' => $request->status_verifikasi,
        ]);

        return redirect()->back()->with('success', 'Status verifikasi tim berhasil diperbarui!');
    }

    // Menghapus data tim, file di storage, dan membersihkan jadwal pertandingan terkait
    public function destroy(Team $team)
    {
        DB::transaction(function () use ($team) {
            // Bersihkan jadwal pertandingan yang melibatkan tim ini (home atau away)
            MatchModel::where('team_home_id', $team->id)
                ->orWhere('team_away_id', $team->id)
                ->delete();

            // Hapus file logo tim jika ada
            if ($team->logo_tim
                && ! Team::where('logo_tim', $team->logo_tim)->whereKeyNot($team->getKey())->exists()
                && Storage::disk('public')->exists($team->logo_tim)) {
                Storage::disk('public')->delete($team->logo_tim);
            }

            // Hapus berkas syarat jika ada
            if ($team->berkas_syarat
                && ! Team::where('berkas_syarat', $team->berkas_syarat)->whereKeyNot($team->getKey())->exists()
                && Storage::disk('public')->exists($team->berkas_syarat)) {
                Storage::disk('public')->delete($team->berkas_syarat);
            }

            // Hapus record tim
            $team->delete();
        });

        return redirect()->route('admin.teams.index')->with('success', 'Data tim dan seluruh berkas terkait berhasil dihapus!');
    }
}
