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
            <section>
                <p class="mb-4 font-heading text-xs font-semibold uppercase tracking-widest text-garuda-pitch">Status Pendaftaran Sekolah</p>
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                    @foreach ($teams as $team)
                        @php
                            [$statusLabel, $statusClass] = match ($team->status_verifikasi) {
                                'approved' => ['Terkonfirmasi & Terverifikasi', 'border-emerald-300 bg-emerald-50 text-emerald-800'],
                                'rejected' => ['Ditolak / Perlu Revisi', 'border-red-300 bg-red-50 text-red-800'],
                                default => ['Menunggu Verifikasi Panitia', 'border-amber-300 bg-amber-50 text-amber-800'],
                            };
                        @endphp
                        <article class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                            <div class="mb-5 flex items-start justify-between gap-4 border-b border-gray-100 pb-4">
                                <div class="flex items-center gap-3">
                                    @if ($team->logo_tim)
                                        <img src="{{ asset('storage/'.$team->logo_tim) }}" alt="Logo {{ $team->nama_tim }}" class="h-14 w-14 rounded-lg border border-gray-200 bg-white object-contain p-1">
                                    @endif
                                    <div>
                                        <span class="font-heading text-xs uppercase tracking-wider text-gray-500">Tim Terdaftar</span>
                                        <h2 class="font-display text-2xl uppercase tracking-wide text-garuda-obsidian">{{ $team->nama_tim }}</h2>
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
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
