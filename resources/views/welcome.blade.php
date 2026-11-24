@extends('layouts.app')

@section('title', 'SMEAN Cup 2026 — SMKN 1 Garut')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <section class="hero-mesh-background relative isolate overflow-hidden rounded-[2.5rem] border border-[#2F8951]">
            <svg
                class="pointer-events-none absolute inset-x-0 bottom-0 z-0 h-32 w-full sm:h-40 lg:h-48"
                viewBox="0 0 1440 190"
                preserveAspectRatio="xMidYMax slice"
                fill="none"
                aria-hidden="true"
            >
                <g stroke="#EAF5E8" stroke-opacity=".24" stroke-width="2.5">
                    <path d="M0 177H1440" />
                    <path d="M0 177V158H54V148H93V177M54 158H93M93 177V152H145V177M145 177V162H197V177" />
                    <path d="M1194 177V154H1247V148H1290V154H1348V150H1397V177M1247 154H1290M1348 150V177M1397 160H1440" />
                    <path d="M269 177V160H314V177M314 177V154H351V177M1077 177V158H1123V177" />
                </g>
                <g stroke="#F1C40F" stroke-opacity=".38" stroke-width="2">
                    <path d="M382 190V153H1058V190M720 153V190" />
                    <path d="M720 161A13 13 0 1 0 720 187A13 13 0 1 0 720 161Z" />
                    <path d="M382 162H412V180H382M1058 162H1028V180H1058" />
                    <path d="M0 177H1440" />
                </g>
            </svg>

            <div class="relative z-10 grid min-h-[520px] grid-cols-1 items-center gap-8 px-6 py-10 sm:px-10 lg:grid-cols-12 lg:px-14 lg:py-12">
                <div class="space-y-6 text-center lg:col-span-7 lg:text-left">
                    <p class="inline-flex items-center gap-2 rounded-full border border-white/20 px-3.5 py-1.5 font-heading text-xs font-semibold uppercase tracking-widest text-garuda-gold">
                        Jawa Barat Series 2026
                    </p>

                    <h1 class="font-display text-5xl uppercase leading-[0.95] tracking-wide text-garuda-white sm:text-7xl">
                        Buktikan Nyali,<br>
                        <span class="text-garuda-gold">Rebut Tahta Juara!</span>
                    </h1>

                    <p class="mx-auto max-w-xl text-base leading-7 text-gray-100 sm:text-lg lg:mx-0">
                        Turnamen futsal antarsekolah se-Jawa Barat persembahan
                        <strong class="font-semibold text-white">SMKN 1 Garut</strong>. Siapkan tim terbaik,
                        junjung sportivitas, dan raih prestasi di Garuda Arena.
                    </p>

                    <div class="flex flex-col items-center justify-center gap-3 pt-2 sm:flex-row lg:justify-start">
                        @guest
                            <a href="{{ route('register') }}" class="w-full rounded-xl bg-garuda-mint px-7 py-3.5 text-center font-heading text-sm font-bold uppercase tracking-wide text-garuda-obsidian transition hover:bg-emerald-400 sm:w-auto">
                                Daftar Tim
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="w-full rounded-xl bg-garuda-mint px-7 py-3.5 text-center font-heading text-sm font-bold uppercase tracking-wide text-garuda-obsidian transition hover:bg-emerald-400 sm:w-auto">
                                Dashboard Tim
                            </a>
                        @endguest
                        <a href="{{ route('public.schedule') }}" class="w-full rounded-xl border border-white/50 px-7 py-3.5 text-center font-heading text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-white hover:text-garuda-pitch sm:w-auto">
                            Jadwal &amp; Live Score
                        </a>
                    </div>

                    <div class="mx-auto grid max-w-lg grid-cols-3 gap-4 border-t border-white/20 pt-6 lg:mx-0">
                        <div>
                            <span class="block font-display text-3xl text-garuda-gold">{{ $teams->count() }}</span>
                            <span class="text-xs uppercase tracking-wide text-gray-200">Tim Terverifikasi</span>
                        </div>
                        <div>
                            <span class="block font-display text-3xl text-garuda-white">Rp 15JT+</span>
                            <span class="text-xs uppercase tracking-wide text-gray-200">Total Hadiah</span>
                        </div>
                        <div>
                            <span class="block font-display text-3xl text-garuda-white">SMKN 1 GARUT</span>
                            <span class="text-xs uppercase tracking-wide text-gray-200">Venue Utama</span>
                        </div>
                    </div>
                </div>

                <div class="relative flex justify-center lg:col-span-5 lg:justify-end">
                    <div class="hero-mascot-backdrop absolute inset-0 rounded-full" aria-hidden="true"></div>
                    <img
                        src="{{ asset('images/mascot-gatam.png') }}"
                        alt="Maskot Gatam SMEAN Cup"
                        class="relative z-10 h-auto w-64 animate-float object-contain sm:w-80 lg:w-full lg:max-w-[400px]"
                    >
                </div>
            </div>
        </section>

        <div class="mt-10 space-y-8">
            <section id="matches" class="scroll-mt-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
                    <div>
                        <p class="font-heading text-xs font-semibold uppercase tracking-widest text-garuda-pitch">Match Center</p>
                        <h2 class="mt-1 font-display text-3xl uppercase text-garuda-obsidian sm:text-4xl">Jadwal &amp; Live Score</h2>
                    </div>
                    <a href="{{ route('public.schedule') }}" class="font-heading text-xs font-bold uppercase tracking-wide text-garuda-pitch hover:underline">
                        Semua pertandingan
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2" id="matches-list">
                    @forelse ($matches as $match)
                        <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm" data-match-id="{{ $match->id }}" data-status="{{ $match->status_match }}">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <span data-match-status class="rounded-full bg-garuda-mint px-3 py-1 text-xs font-bold uppercase text-garuda-obsidian">{{ $match->status_match }}</span>
                                <span class="text-right text-xs text-gray-500">{{ $match->babak }} <span aria-hidden="true">·</span> {{ $match->lapangan }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3 text-center">
                                <span class="w-5/12 font-heading text-sm font-bold text-garuda-obsidian">{{ $match->homeTeam->nama_tim }}</span>
                                <span data-match-score class="w-2/12 whitespace-nowrap font-display text-2xl text-garuda-pitch">{{ $match->skor_home }} - {{ $match->skor_away }}</span>
                                <span class="w-5/12 font-heading text-sm font-bold text-garuda-obsidian">{{ $match->awayTeam->nama_tim }}</span>
                            </div>
                            <p class="mt-4 text-center text-xs text-gray-500">{{ $match->waktu_tanding }}</p>
                        </article>
                    @empty
                        <p class="col-span-full py-8 text-center text-sm text-gray-500">Jadwal pertandingan akan segera diumumkan.</p>
                    @endforelse
                </div>
                <p id="score-update-status" class="mt-3 min-h-5 text-center text-xs text-gray-500" role="status" aria-live="polite"></p>
            </section>

            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-6">
                    <p class="font-heading text-xs font-semibold uppercase tracking-widest text-garuda-pitch">Peserta</p>
                    <h2 class="mt-1 font-display text-3xl uppercase text-garuda-obsidian sm:text-4xl">Tim Terverifikasi</h2>
                </div>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
                    @forelse ($teams as $team)
                        <article class="rounded-xl border border-gray-200 bg-white p-4 text-center">
                            @if ($team->logo_tim)
                                <img src="{{ asset('storage/'.$team->logo_tim) }}" alt="Logo {{ $team->nama_tim }}" class="mx-auto mb-3 h-16 w-16 rounded-full border border-gray-200 object-cover">
                            @else
                                <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 font-heading text-xs font-bold text-garuda-pitch">TIM</div>
                            @endif
                            <h3 class="font-heading text-sm font-bold text-garuda-obsidian">{{ $team->nama_tim }}</h3>
                            <p class="mt-1 text-xs text-gray-500">{{ $team->asal_sekolah }}</p>
                        </article>
                    @empty
                        <p class="col-span-full py-8 text-center text-sm text-gray-500">Belum ada tim yang disetujui.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const scoreUpdateStatus = document.getElementById('score-update-status');

        async function refreshLiveScores() {
            try {
                const response = await fetch('{{ url('/api/live-scores') }}', {
                    headers: { Accept: 'application/json' },
                    cache: 'no-store',
                });
                if (!response.ok) {
                    throw new Error(`Request failed (${response.status})`);
                }

                const result = await response.json();
                if (!result.success || !Array.isArray(result.data)) {
                    throw new Error('Respons API live score tidak valid');
                }

                for (const match of result.data) {
                    const card = document.querySelector(`[data-match-id="${match.id}"]`);
                    if (!card) {
                        continue;
                    }

                    card.dataset.status = match.status_match;
                    card.querySelector('[data-match-status]').textContent = match.status_match.toUpperCase();
                    card.querySelector('[data-match-score]').textContent = `${match.skor_home} - ${match.skor_away}`;
                }

                scoreUpdateStatus.textContent = `Skor diperbarui ${new Date().toLocaleTimeString()}`;
            } catch (error) {
                scoreUpdateStatus.textContent = 'Pembaruan skor gagal dimuat. Coba lagi sebentar.';
                console.error('Gagal memperbarui live score:', error);
            }
        }

        refreshLiveScores();
        window.setInterval(refreshLiveScores, 15000);
    </script>
@endpush
