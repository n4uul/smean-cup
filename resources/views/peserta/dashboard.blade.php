<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8 border-b border-garuda-pitch pb-6">
            <div class="mb-1 flex items-center gap-2 font-heading text-xs uppercase tracking-widest text-garuda-mint">
                <span class="h-2 w-2 rounded-full bg-garuda-mint"></span>
                Area Resmi Tim Terdaftar
            </div>
            <h1 class="font-display text-4xl uppercase tracking-wider text-garuda-white sm:text-5xl">
                Dashboard <span class="text-garuda-gold">Tim Kamu</span>
            </h1>
        </div>

        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-xl border border-garuda-mint/40 bg-emerald-950 p-4 font-sans text-sm text-garuda-mint" role="status">
                <span aria-hidden="true">✓</span>
                {{ session('success') }}
            </div>
        @endif

        @if (!$team)
            <section class="max-w-3xl rounded-2xl border border-[#315635] bg-garuda-pitch p-6 shadow-sm sm:p-8">
                <h2 class="mb-2 font-heading text-xl font-bold uppercase tracking-wide text-garuda-white">
                    Formulir Pendaftaran Turnamen
                </h2>
                <p class="mb-6 font-sans text-sm text-gray-300">
                    Lengkapi data resmi tim futsal sekolahmu untuk mengikuti
                    <strong class="text-garuda-gold">SMEAN Cup 2026</strong>.
                </p>

                <form action="{{ route('teams.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="nama_tim" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Nama Tim Futsal</label>
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
                                aria-describedby="asal_sekolah-error"
                                class="mt-1 w-full rounded-xl border border-gray-700 bg-garuda-obsidian px-4 py-3 text-garuda-white transition focus:border-garuda-mint focus:outline-none focus:ring-garuda-mint"
                            >
                            @error('asal_sekolah')
                                <span id="asal_sekolah-error" class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="nama_pelatih" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Nama Pelatih / Official</label>
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

                        <div>
                            <label for="no_wa" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">No. WhatsApp (Aktif)</label>
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
                            <label for="logo_tim" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Logo Tim (PNG/JPG)</label>
                            <input
                                id="logo_tim"
                                type="file"
                                name="logo_tim"
                                accept=".jpg,.jpeg,.png"
                                aria-invalid="{{ $errors->has('logo_tim') ? 'true' : 'false' }}"
                                aria-describedby="logo_tim-help logo_tim-error"
                                class="w-full text-sm text-gray-400 file:mr-4 file:rounded-xl file:border-0 file:bg-garuda-pitch file:px-4 file:py-2.5 file:font-heading file:text-xs file:uppercase file:text-garuda-mint hover:file:bg-emerald-950"
                            >
                            <p id="logo_tim-help" class="mt-1 text-xs text-gray-500">JPG atau PNG, maksimal 2 MB.</p>
                            @error('logo_tim')
                                <span id="logo_tim-error" class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="berkas_syarat" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-300">Berkas Syarat</label>
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
            @php
                $statusColors = [
                    'pending' => 'border-amber-500/40 bg-amber-500/20 text-garuda-gold',
                    'approved' => 'border-emerald-500/40 bg-emerald-500/20 text-garuda-mint',
                    'rejected' => 'border-red-500/40 bg-red-500/20 text-red-400',
                ];
                $statusText = [
                    'pending' => 'Menunggu Verifikasi Panitia',
                    'approved' => 'Terkonfirmasi & Terverifikasi',
                    'rejected' => 'Ditolak / Perlu Revisi',
                ];
            @endphp

            <section class="max-w-2xl rounded-2xl border border-[#315635] bg-garuda-pitch p-6 shadow-sm sm:p-8">
                <div class="mb-6 flex flex-col justify-between gap-4 border-b border-garuda-pitch pb-4 sm:flex-row sm:items-center">
                    <div>
                        <span class="font-sans text-xs uppercase text-gray-400">Status Tim Anda</span>
                        <h2 class="font-display text-3xl uppercase tracking-wider text-garuda-white">{{ $team->nama_tim }}</h2>
                    </div>
                    <span class="inline-flex w-fit rounded-full border px-4 py-1.5 font-heading text-xs font-bold uppercase tracking-wider {{ $statusColors[$team->status_verifikasi] ?? 'border-gray-700 bg-gray-700 text-gray-300' }}">
                        {{ $statusText[$team->status_verifikasi] ?? ucfirst($team->status_verifikasi) }}
                    </span>
                </div>

                @if ($team->logo_tim)
                    <div class="mb-6">
                        <img src="{{ asset('storage/'.$team->logo_tim) }}" alt="Logo {{ $team->nama_tim }}" class="h-20 w-20 rounded-xl border border-garuda-pitch bg-garuda-obsidian object-contain p-2">
                    </div>
                @endif

                <dl class="grid grid-cols-1 gap-4 font-sans text-sm text-gray-300 sm:grid-cols-2">
                    <div class="rounded-xl border border-gray-800 bg-garuda-obsidian p-4">
                        <dt class="font-heading text-xs uppercase text-gray-500">Asal Sekolah</dt>
                        <dd class="mt-1 text-base font-semibold text-garuda-white">{{ $team->asal_sekolah }}</dd>
                    </div>
                    <div class="rounded-xl border border-gray-800 bg-garuda-obsidian p-4">
                        <dt class="font-heading text-xs uppercase text-gray-500">Nama Pelatih</dt>
                        <dd class="mt-1 text-base font-semibold text-garuda-white">{{ $team->nama_pelatih }}</dd>
                    </div>
                    <div class="rounded-xl border border-gray-800 bg-garuda-obsidian p-4">
                        <dt class="font-heading text-xs uppercase text-gray-500">No. WhatsApp</dt>
                        <dd class="mt-1 text-base font-semibold text-garuda-white">{{ $team->no_wa }}</dd>
                    </div>
                    <div class="rounded-xl border border-gray-800 bg-garuda-obsidian p-4">
                        <dt class="font-heading text-xs uppercase text-gray-500">Berkas Syarat</dt>
                        <dd class="mt-1">
                            @if ($team->berkas_syarat)
                                <a href="{{ asset('storage/'.$team->berkas_syarat) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-semibold text-garuda-mint hover:underline">
                                    Lihat berkas upload
                                </a>
                            @else
                                <span class="text-gray-500">Belum ada berkas</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </section>
        @endif
    </div>
</x-app-layout>
