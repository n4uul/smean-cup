<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Throwable;

class TeamController extends Controller
{
    // Menampilkan halaman pendaftaran / status tim milik user yang sedang login
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.teams.index');
        }

        $teams = auth()->user()
            ->teams()
            ->with(['players' => fn ($query) => $query->orderBy('nomor_punggung')])
            ->oldest()
            ->get();

        return view('peserta.dashboard', compact('teams'));
    }

    // Menyimpan data pendaftaran tim baru
    public function store(Request $request)
    {
        $teamName = $request->input('nama_tim');
        $schoolName = $request->input('asal_sekolah');
        $kategori = $request->input('kategori');
        $request->merge([
            'nama_tim' => is_string($teamName) ? trim($teamName) : $teamName,
            'asal_sekolah' => is_string($schoolName) ? trim($schoolName) : $schoolName,
            'kategori' => is_string($kategori) ? Str::lower(trim($kategori)) : $kategori,
        ]);

        $validated = $request->validate([
            'nama_tim' => 'required|string|max:255',
            'kategori' => ['required', 'string', Rule::in([Team::KATEGORI_PUTRA, Team::KATEGORI_PUTRI])],
            'jumlah_tim' => 'required|integer|between:1,5',
            'asal_sekolah' => 'required|string|max:255',
            'nama_pelatih' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'logo_tim' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'berkas_syarat' => 'nullable|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $storedPaths = [];

        try {
            DB::transaction(function () use ($request, $validated, &$storedPaths) {
                $user = $request->user()->newQuery()
                    ->lockForUpdate()
                    ->findOrFail($request->user()->getKey());

                if ($user->teams()->exists()) {
                    throw ValidationException::withMessages([
                        'nama_tim' => 'Akun ini sudah mengirim pendaftaran tim.',
                    ]);
                }

                $schoolKey = Str::upper($validated['asal_sekolah']);
                $schoolTeams = Team::query()
                    ->whereRaw('UPPER(TRIM(asal_sekolah)) = ?', [$schoolKey])
                    ->whereIn('status_verifikasi', ['pending', 'approved'])
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                $teamCount = $schoolTeams->count();
                $requestedCount = (int) $validated['jumlah_tim'];
                if ($teamCount + $requestedCount > 5) {
                    throw ValidationException::withMessages([
                        'jumlah_tim' => $teamCount >= 5
                            ? 'Kuota pendaftaran untuk sekolah ini sudah penuh (Maksimal 5 tim per sekolah).'
                            : "Sisa kuota pendaftaran untuk sekolah ini hanya ".(5 - $teamCount).' tim.',
                    ]);
                }

                $firstTeam = $schoolTeams->first();
                $firstTeamName = null;
                if ($teamCount > 0 && ! preg_match('/ [A-E]$/u', $firstTeam->nama_tim)) {
                    $firstTeamName = Str::substr(trim($firstTeam->nama_tim), 0, 253).' A';
                }

                $baseName = trim($validated['nama_tim']);
                $teamNames = [];
                for ($index = 0; $index < $requestedCount; $index++) {
                    $letterIndex = $teamCount + $index;
                    $teamNames[] = $letterIndex === 0 && $requestedCount === 1
                        ? $baseName
                        : Str::substr($baseName, 0, 253).' '.chr(ord('A') + $letterIndex);
                }

                $namesToCheck = $teamNames;
                if ($firstTeamName !== null) {
                    $namesToCheck[] = $firstTeamName;
                }

                Validator::make(
                    ['nama_tim' => $namesToCheck],
                    ['nama_tim.*' => ['required', 'string', 'max:255', 'distinct', Rule::unique('teams', 'nama_tim')]]
                )->validate();

                $logoPath = null;
                if ($request->hasFile('logo_tim')) {
                    $logoPath = $request->file('logo_tim')->store('logos', 'public');
                    if ($logoPath === false) {
                        throw new \RuntimeException('Logo sekolah gagal disimpan.');
                    }
                    $storedPaths[] = $logoPath;
                }

                $berkasPath = null;
                if ($request->hasFile('berkas_syarat')) {
                    $berkasPath = $request->file('berkas_syarat')->store('berkas', 'public');
                    if ($berkasPath === false) {
                        throw new \RuntimeException('Berkas persyaratan gagal disimpan.');
                    }
                    $storedPaths[] = $berkasPath;
                }

                if ($firstTeamName !== null) {
                    $firstTeam->update(['nama_tim' => $firstTeamName]);
                }

                foreach ($teamNames as $name) {
                    Team::create([
                        'user_id' => $user->id,
                        'nama_tim' => $name,
                        'kategori' => $validated['kategori'],
                        'asal_sekolah' => $validated['asal_sekolah'],
                        'nama_pelatih' => $validated['nama_pelatih'],
                        'no_wa' => $validated['no_wa'],
                        'logo_tim' => $logoPath,
                        'berkas_syarat' => $berkasPath,
                        'status_verifikasi' => 'pending',
                    ]);
                }
            });
        } catch (Throwable $exception) {
            foreach (array_unique($storedPaths) as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        }

        return redirect()->route('dashboard')->with('success', 'Pendaftaran tim berhasil dikirim dan sedang diverifikasi!');
    }
}
