<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

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
}
