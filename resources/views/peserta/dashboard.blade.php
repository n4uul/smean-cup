<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 border-b border-garuda-pitch pb-6">
            <div class="mb-1 flex items-center gap-2 font-heading text-xs uppercase tracking-widest text-garuda-mint">
                <span class="h-2 w-2 rounded-full bg-garuda-mint"></span>
                Area Resmi Tim Terdaftar
            </div>
            <h1 class="font-display text-4xl uppercase tracking-wider text-garuda-obsidian sm:text-5xl">
                Dashboard <span class="text-garuda-gold">Tim Kamu</span>
            </h1>
        </div>

        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-garuda-mint/40 bg-emerald-950 p-4 font-sans text-sm text-garuda-mint" role="status">
                <span aria-hidden="true">✓</span>
                {{ session('success') }}
            </div>
        @endif

        @if ($teams->isEmpty())
            <section class="max-w-3xl rounded-2xl border border-[#315635] bg-garuda-pitch p-6 shadow-sm sm:p-8">
                <h2 class="mb-2 font-heading text-xl font-bold uppercase tracking-wide text-garuda-white">
                    Formulir Pendaftaran Turnamen
                </h2>
                <p class="mb-6 font-sans text-sm text-gray-300">
                    Lengkapi data resmi tim futsal sekolahmu untuk mengikuti
                    <strong class="text-garuda-gold">SMEAN Cup 2026</strong>.
                </p>

                <div class="mb-6 rounded-xl border border-garuda-mint/30 bg-garuda-obsidian/50 p-4 text-sm text-gray-200">
                    Satu akun penanggung jawab dapat mendaftarkan hingga
                    <strong class="text-garuda-white">5 tim</strong> untuk sekolah yang sama. Nama tim akan diberi
                    label otomatis <strong class="text-garuda-gold">A–E</strong> jika mengirim lebih dari satu tim.
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-400/40 bg-red-950/60 p-4 text-sm text-red-200" role="alert">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('teams.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="nama_tim" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Nama Dasar Tim / Sekolah</label>
                            <input
                                id="nama_tim"
                                type="text"
                                name="nama_tim"
                                value="{{ old('nama_tim') }}"
                                maxlength="255"
                                required
                                aria-invalid="{{ $errors->has('nama_tim') ? 'true' : 'false' }}"
                                aria-describedby="nama_tim-error"
                                class="mt-1 w-full rounded-xl border border-gray-700 bg-garuda-obsidian px-4 py-3 text-garuda-white transition focus:border-garuda-mint focus:outline-none focus:ring-garuda-mint"
                            >
                            @error('nama_tim')
                                <span id="nama_tim-error" class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="jumlah_tim" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Jumlah Tim yang Dikirim</label>
                            <select
                                id="jumlah_tim"
                                name="jumlah_tim"
                                required
                                aria-invalid="{{ $errors->has('jumlah_tim') ? 'true' : 'false' }}"
                                aria-describedby="jumlah_tim-info jumlah_tim-error"
                                class="mt-1 w-full rounded-xl border border-gray-700 bg-garuda-obsidian px-4 py-3 text-garuda-white transition focus:border-garuda-mint focus:outline-none focus:ring-garuda-mint"
                            >
                                @foreach (range(1, 5) as $jumlah)
                                    <option value="{{ $jumlah }}" @selected(old('jumlah_tim', 1) == $jumlah)>{{ $jumlah }} tim</option>
                                @endforeach
                            </select>
                            <span id="jumlah_tim-info" class="mt-1 block text-xs text-gray-400">Untuk lebih dari 1 tim, sistem menambahkan label A–E pada nama setiap tim.</span>
                            @error('jumlah_tim')
                                <span id="jumlah_tim-error" class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="asal_sekolah" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Asal Sekolah</label>
                            <input
                                id="asal_sekolah"
                                type="text"
                                name="asal_sekolah"
                                value="{{ old('asal_sekolah') }}"
                                maxlength="255"
                                placeholder="Contoh: SMKN 1 Garut"
                                required
                                aria-invalid="{{ $errors->has('asal_sekolah') ? 'true' : 'false' }}"
                                aria-describedby="asal_sekolah-info asal_sekolah-error"
                                class="mt-1 w-full rounded-xl border border-gray-700 bg-garuda-obsidian px-4 py-3 text-garuda-white transition focus:border-garuda-mint focus:outline-none focus:ring-garuda-mint"
                            >
                            <span id="asal_sekolah-info" class="mt-1 block text-xs text-gray-400">Kuota dan label tim dihitung berdasarkan nama sekolah, tanpa membedakan kapitalisasi atau spasi di awal/akhir.</span>
                            @error('asal_sekolah')
                                <span id="asal_sekolah-error" class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="nama_pelatih" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Nama Pelatih / Official Penanggung Jawab</label>
                            <input
                                id="nama_pelatih"
                                type="text"
                                name="nama_pelatih"
                                value="{{ old('nama_pelatih') }}"
                                maxlength="255"
                                required
                                aria-invalid="{{ $errors->has('nama_pelatih') ? 'true' : 'false' }}"
                                aria-describedby="nama_pelatih-error"
                                class="mt-1 w-full rounded-xl border border-gray-700 bg-garuda-obsidian px-4 py-3 text-garuda-white transition focus:border-garuda-mint focus:outline-none focus:ring-garuda-mint"
                            >
                            @error('nama_pelatih')
                                <span id="nama_pelatih-error" class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="kategori" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Kategori Tim</label>
                            <select
                                id="kategori"
                                name="kategori"
                                required
                                aria-invalid="{{ $errors->has('kategori') ? 'true' : 'false' }}"
                                aria-describedby="kategori-info kategori-error"
                                class="mt-1 w-full rounded-xl border border-gray-700 bg-garuda-obsidian px-4 py-3 text-garuda-white transition focus:border-garuda-mint focus:outline-none focus:ring-garuda-mint"
                            >
                                <option value="" disabled @selected(old('kategori') === null)>Pilih Kategori</option>
                                <option value="putra" @selected(old('kategori') === 'putra')>Putra</option>
                                <option value="putri" @selected(old('kategori') === 'putri')>Putri</option>
                            </select>
                            <span id="kategori-info" class="mt-1 block text-xs text-gray-400">Kategori berlaku untuk semua tim yang didaftarkan pada pengajuan ini.</span>
                            @error('kategori')
                                <span id="kategori-error" class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="no_wa" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">No. WhatsApp Penanggung Jawab</label>
                            <input
                                id="no_wa"
                                type="tel"
                                name="no_wa"
                                value="{{ old('no_wa') }}"
                                maxlength="20"
                                placeholder="08xxxxxxxxxx"
                                required
                                aria-invalid="{{ $errors->has('no_wa') ? 'true' : 'false' }}"
                                aria-describedby="no_wa-error"
                                class="mt-1 w-full rounded-xl border border-gray-700 bg-garuda-obsidian px-4 py-3 text-garuda-white transition focus:border-garuda-mint focus:outline-none focus:ring-garuda-mint"
                            >
                            @error('no_wa')
                                <span id="no_wa-error" class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="logo_tim" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Logo Sekolah / Tim (PNG/JPG)</label>
                            <input
                                id="logo_tim"
                                type="file"
                                name="logo_tim"
                                accept=".jpg,.jpeg,.png"
                                aria-invalid="{{ $errors->has('logo_tim') ? 'true' : 'false' }}"
                                aria-describedby="logo_tim-help logo_tim-error"
                                class="w-full text-sm text-gray-400 file:mr-4 file:rounded-xl file:border-0 file:bg-garuda-pitch file:px-4 file:py-2.5 file:font-heading file:text-xs file:uppercase file:text-garuda-mint hover:file:bg-emerald-950"
                            >
                            <p id="logo_tim-help" class="mt-1 text-xs text-gray-500">JPG atau PNG, maksimal 2 MB. Logo ini digunakan bersama untuk semua tim yang didaftarkan.</p>
                            @error('logo_tim')
                                <span id="logo_tim-error" class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="berkas_syarat" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Berkas Persyaratan Sekolah</label>
                            <input
                                id="berkas_syarat"
                                type="file"
                                name="berkas_syarat"
                                accept=".pdf,.jpg,.jpeg,.png"
                                aria-invalid="{{ $errors->has('berkas_syarat') ? 'true' : 'false' }}"
                                aria-describedby="berkas_syarat-help berkas_syarat-error"
                                class="w-full text-sm text-gray-400 file:mr-4 file:rounded-xl file:border-0 file:bg-garuda-pitch file:px-4 file:py-2.5 file:font-heading file:text-xs file:uppercase file:text-garuda-mint hover:file:bg-emerald-950"
                            >
                            <p id="berkas_syarat-help" class="mt-1 text-xs text-gray-500">PDF, JPG, atau PNG, maksimal 5 MB.</p>
                            @error('berkas_syarat')
                                <span id="berkas_syarat-error" class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="rounded-xl bg-garuda-mint px-8 py-3.5 font-heading font-bold uppercase tracking-wider text-garuda-obsidian transition hover:bg-emerald-400">
                            Kirim Pendaftaran Tim
                        </button>
                    </div>
                </form>
            </section>
        @else
            <section
                x-data="{ openRosterTeamId: @js((int) old('roster_team_id', session('roster_team_id', 0))) }"
                @keydown.escape.window="openRosterTeamId = 0"
            >
                <p class="mb-4 font-heading text-xs font-semibold uppercase tracking-widest text-garuda-pitch">Status Pendaftaran Sekolah</p>
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                    @foreach ($teams as $team)
                        @php
                            [$statusLabel, $statusClass] = match ($team->status_verifikasi) {
                                'approved' => ['Terkonfirmasi & Terverifikasi', 'border-emerald-300 bg-emerald-50 text-emerald-800'],
                                'rejected' => ['Ditolak / Perlu Revisi', 'border-red-300 bg-red-50 text-red-800'],
                                default => ['Menunggu Verifikasi Panitia', 'border-amber-300 bg-amber-50 text-amber-800'],
                            };
                            $playerCount = $team->players->count();
                            $isTeamRosterError = $errors->any() && (int) old('roster_team_id') === $team->id;
                        @endphp
                        <article class="flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                            <div>
                                <div class="mb-5 flex items-start justify-between gap-4 border-b border-gray-100 pb-4">
                                    <div class="flex items-center gap-3">
                                        @if ($team->logo_tim)
                                            <img src="{{ asset('storage/'.$team->logo_tim) }}" alt="Logo {{ $team->nama_tim }}" class="h-14 w-14 rounded-lg border border-gray-200 bg-white object-contain p-1">
                                        @endif
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="font-heading text-xs uppercase tracking-wider text-gray-500">Tim Terdaftar</span>
                                                <x-category-badge :kategori="$team->kategori ?? 'putra'" />
                                            </div>
                                            <h2 class="mt-1 font-display text-2xl uppercase tracking-wide text-garuda-obsidian">{{ $team->nama_tim }}</h2>
                                        </div>
                                    </div>
                                    <span class="inline-flex rounded-full border px-3 py-1 font-heading text-[10px] font-bold uppercase {{ $statusClass }}">{{ $statusLabel }}</span>
                                </div>
                                <dl class="grid grid-cols-1 gap-3 font-sans text-sm sm:grid-cols-2">
                                    <div>
                                        <dt class="font-heading text-xs uppercase text-gray-500">Asal Sekolah</dt>
                                        <dd class="mt-1 font-semibold text-garuda-obsidian">{{ $team->asal_sekolah }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-heading text-xs uppercase text-gray-500">Penanggung Jawab</dt>
                                        <dd class="mt-1 font-semibold text-garuda-obsidian">{{ $team->nama_pelatih }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-heading text-xs uppercase text-gray-500">No. WhatsApp</dt>
                                        <dd class="mt-1 font-semibold text-garuda-obsidian">{{ $team->no_wa }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-heading text-xs uppercase text-gray-500">Berkas Persyaratan</dt>
                                        <dd class="mt-1">
                                            @if ($team->berkas_syarat)
                                                <a href="{{ asset('storage/'.$team->berkas_syarat) }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-garuda-pitch hover:underline">Lihat berkas</a>
                                            @else
                                                <span class="text-gray-500">Belum ada berkas</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Section Roster Pemain -->
                            <div class="mt-6 border-t border-gray-100 pt-5">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-heading text-xs font-bold uppercase tracking-wider text-garuda-obsidian">Roster Pemain</h3>
                                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 font-heading text-[10px] font-bold uppercase text-garuda-pitch">
                                                {{ $playerCount }} / {{ \App\Models\Team::MAX_PLAYERS }} Pemain
                                            </span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Daftarkan pemain beserta nomor punggung dan NISN untuk verifikasi pertandingan.</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="openRosterTeamId = {{ $team->id }}"
                                        class="inline-flex items-center gap-2 rounded-xl bg-garuda-obsidian px-4 py-2.5 font-heading text-xs font-bold uppercase tracking-wider text-garuda-gold transition hover:bg-garuda-pitch hover:text-white focus:outline-none focus:ring-2 focus:ring-garuda-pitch focus:ring-offset-2"
                                    >
                                        <span>Kelola Roster Pemain</span>
                                    </button>
                                </div>

                                @if ($team->players->isNotEmpty())
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach ($team->players->take(6) as $player)
                                            <span class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs font-medium text-garuda-obsidian">
                                                <span class="rounded-full bg-garuda-obsidian px-1.5 py-0.5 font-display text-[11px] leading-none text-garuda-gold">#{{ $player->nomor_punggung }}</span>
                                                <span class="max-w-[140px] truncate">{{ $player->nama_lengkap }}</span>
                                                <span class="text-[10px] font-semibold uppercase text-gray-400">{{ $player->posisi }}</span>
                                            </span>
                                        @endforeach
                                        @if ($playerCount > 6)
                                            <span class="inline-flex items-center rounded-full border border-dashed border-gray-300 px-3 py-1 text-xs font-semibold text-gray-500">
                                                +{{ $playerCount - 6 }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <p class="mt-3 rounded-xl border border-dashed border-gray-200 bg-gray-50/70 px-4 py-3 text-xs text-gray-500">
                                        Belum ada pemain yang didaftarkan pada tim ini.
                                    </p>
                                @endif
                            </div>
                        </article>

                        <!-- Modal Kelola Roster Pemain -->
                        <div
                            x-show="openRosterTeamId === {{ $team->id }}"
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-garuda-obsidian/70 p-4 backdrop-blur-sm"
                            role="dialog"
                            aria-modal="true"
                            aria-labelledby="roster-modal-title-{{ $team->id }}"
                        >
                            <div
                                x-show="openRosterTeamId === {{ $team->id }}"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                @click.outside="openRosterTeamId = 0"
                                class="relative max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl border border-gray-200 bg-white p-6 shadow-2xl sm:p-8"
                            >
                                <div class="mb-6 flex items-start justify-between gap-4 border-b border-gray-100 pb-4">
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-heading text-xs font-bold uppercase tracking-widest text-garuda-pitch">Kelola Roster Pemain</span>
                                            <x-category-badge :kategori="$team->kategori ?? 'putra'" />
                                            <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 font-heading text-[10px] font-bold uppercase text-garuda-pitch">
                                                {{ $playerCount }} / {{ \App\Models\Team::MAX_PLAYERS }} Pemain
                                            </span>
                                        </div>
                                        <h3 id="roster-modal-title-{{ $team->id }}" class="mt-1 font-display text-3xl uppercase tracking-wide text-garuda-obsidian">
                                            {{ $team->nama_tim }}
                                        </h3>
                                    </div>
                                    <button
                                        type="button"
                                        @click="openRosterTeamId = 0"
                                        aria-label="Tutup modal roster {{ $team->nama_tim }}"
                                        class="rounded-lg border border-gray-200 p-2 text-gray-500 transition hover:bg-gray-100 hover:text-garuda-obsidian"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                @if ($isTeamRosterError)
                                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                                        <ul class="list-inside list-disc">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if ($playerCount < \App\Models\Team::MAX_PLAYERS)
                                    <form action="{{ route('players.store', $team) }}" method="POST" class="mb-7 rounded-2xl border border-gray-200 bg-gray-50/70 p-5">
                                        @csrf
                                        <input type="hidden" name="roster_team_id" value="{{ $team->id }}">
                                        <h4 class="mb-4 font-heading text-xs font-bold uppercase tracking-wider text-garuda-obsidian">Tambah Pemain Baru</h4>
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                            <div class="sm:col-span-2">
                                                <label for="nama_lengkap_{{ $team->id }}" class="mb-1 block font-heading text-[11px] uppercase tracking-wider text-gray-600">Nama Lengkap</label>
                                                <input
                                                    id="nama_lengkap_{{ $team->id }}"
                                                    type="text"
                                                    name="nama_lengkap"
                                                    value="{{ $isTeamRosterError ? old('nama_lengkap') : '' }}"
                                                    maxlength="255"
                                                    placeholder="Nama sesuai kartu pelajar"
                                                    required
                                                    class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-garuda-obsidian focus:border-garuda-pitch focus:outline-none"
                                                >
                                            </div>
                                            <div>
                                                <label for="nomor_punggung_{{ $team->id }}" class="mb-1 block font-heading text-[11px] uppercase tracking-wider text-gray-600">No. Punggung</label>
                                                <input
                                                    id="nomor_punggung_{{ $team->id }}"
                                                    type="number"
                                                    name="nomor_punggung"
                                                    value="{{ $isTeamRosterError ? old('nomor_punggung') : '' }}"
                                                    min="1"
                                                    max="99"
                                                    placeholder="1-99"
                                                    required
                                                    class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-garuda-obsidian focus:border-garuda-pitch focus:outline-none"
                                                >
                                            </div>
                                            <div>
                                                <label for="posisi_{{ $team->id }}" class="mb-1 block font-heading text-[11px] uppercase tracking-wider text-gray-600">Posisi</label>
                                                <select
                                                    id="posisi_{{ $team->id }}"
                                                    name="posisi"
                                                    required
                                                    class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-garuda-obsidian focus:border-garuda-pitch focus:outline-none"
                                                >
                                                    @foreach (\App\Models\Player::POSISI_LIST as $posisi)
                                                        <option value="{{ $posisi }}" @selected(($isTeamRosterError ? old('posisi', 'Flank') : 'Flank') === $posisi)>{{ $posisi }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="sm:col-span-2 lg:col-span-3">
                                                <label for="nisn_{{ $team->id }}" class="mb-1 block font-heading text-[11px] uppercase tracking-wider text-gray-600">NISN (Opsional — Verifikasi Siswa SMP)</label>
                                                <input
                                                    id="nisn_{{ $team->id }}"
                                                    type="text"
                                                    name="nisn"
                                                    value="{{ $isTeamRosterError ? old('nisn') : '' }}"
                                                    maxlength="30"
                                                    placeholder="Contoh: 0081234567"
                                                    class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-garuda-obsidian focus:border-garuda-pitch focus:outline-none"
                                                >
                                            </div>
                                            <div class="flex items-end">
                                                <button
                                                    type="submit"
                                                    class="w-full rounded-xl bg-garuda-mint px-5 py-2.5 font-heading text-xs font-bold uppercase tracking-wider text-garuda-obsidian shadow-sm transition hover:bg-emerald-400"
                                                >
                                                    + Tambah Pemain
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs font-medium text-amber-800">
                                        Kuota maksimal roster ({{ \App\Models\Team::MAX_PLAYERS }} pemain) untuk tim ini telah terpenuhi. Hapus pemain terlebih dahulu jika ingin mengganti.
                                    </div>
                                @endif

                                <div>
                                    <h4 class="mb-3 font-heading text-xs font-bold uppercase tracking-wider text-gray-500">Daftar Pemain Terdaftar</h4>
                                    @if ($team->players->isEmpty())
                                        <div class="rounded-xl border border-dashed border-gray-200 py-8 text-center text-sm text-gray-500">
                                            Belum ada pemain di roster tim ini. Gunakan formulir di atas untuk menambahkan pemain.
                                        </div>
                                    @else
                                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                                            <table class="w-full border-collapse text-left text-sm">
                                                <thead class="bg-gray-50 font-heading text-[11px] uppercase tracking-wider text-gray-500">
                                                    <tr class="border-b border-gray-200">
                                                        <th scope="col" class="px-4 py-3">No. Punggung</th>
                                                        <th scope="col" class="px-4 py-3">Nama Pemain</th>
                                                        <th scope="col" class="px-4 py-3">Posisi</th>
                                                        <th scope="col" class="px-4 py-3">NISN</th>
                                                        <th scope="col" class="px-4 py-3 text-center">Gol</th>
                                                        <th scope="col" class="px-4 py-3 text-right">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 bg-white">
                                                    @foreach ($team->players as $player)
                                                        <tr class="hover:bg-gray-50/80">
                                                            <td class="px-4 py-3">
                                                                <span class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full bg-garuda-obsidian px-2.5 py-0.5 font-display text-sm tracking-wider text-garuda-gold">
                                                                    #{{ $player->nomor_punggung }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 font-semibold text-garuda-obsidian">{{ $player->nama_lengkap }}</td>
                                                            <td class="px-4 py-3">
                                                                <span class="inline-flex rounded-full border border-gray-200 bg-gray-100 px-2.5 py-0.5 font-heading text-[10px] font-bold uppercase tracking-wider text-gray-700">
                                                                    {{ $player->posisi }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $player->nisn ?: '-' }}</td>
                                                            <td class="px-4 py-3 text-center font-display text-lg text-garuda-pitch">{{ $player->jumlah_gol }}</td>
                                                            <td class="px-4 py-3 text-right">
                                                                <form action="{{ route('players.destroy', $player) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button
                                                                        type="submit"
                                                                        class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 font-heading text-[11px] font-bold uppercase text-red-600 transition hover:bg-red-600 hover:text-white"
                                                                    >
                                                                        Hapus
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
