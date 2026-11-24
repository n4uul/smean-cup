<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    // Menampilkan halaman pendaftaran / status tim milik user yang sedang login
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.teams.index');
        }

        $team = auth()->user()->team;

        return view('peserta.dashboard', compact('team'));
    }

    // Menyimpan data pendaftaran tim baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_tim' => 'required|string|max:255|unique:teams,nama_tim',
            'asal_sekolah' => 'required|string|max:255',
            'nama_pelatih' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'logo_tim' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'berkas_syarat' => 'nullable|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo_tim')) {
            $logoPath = $request->file('logo_tim')->store('logos', 'public');
        }

        $berkasPath = null;
        if ($request->hasFile('berkas_syarat')) {
            $berkasPath = $request->file('berkas_syarat')->store('berkas', 'public');
        }

        Team::create([
            'user_id' => auth()->id(),
            'nama_tim' => $request->nama_tim,
            'asal_sekolah' => $request->asal_sekolah,
            'nama_pelatih' => $request->nama_pelatih,
            'no_wa' => $request->no_wa,
            'logo_tim' => $logoPath,
            'berkas_syarat' => $berkasPath,
            'status_verifikasi' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Pendaftaran tim berhasil dikirim dan sedang diverifikasi!');
    }
}
