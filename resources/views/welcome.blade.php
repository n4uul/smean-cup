@extends('layouts.app')

@section('title', 'SMEAN Cup 2026 — SMKN 1 Garut')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        <section class="hero-mesh-background relative isolate overflow-hidden rounded-[2.5rem] border border-emerald-500/30 shadow-2xl shadow-emerald-950/20">
            <!-- Decorative geometric background pattern (ITB-style soft motifs) -->
            <div class="pointer-events-none absolute inset-0 z-0 opacity-15" aria-hidden="true">
                <svg class="h-full w-full" viewBox="0 0 1200 600" fill="none" preserveAspectRatio="none">
                    <!-- Subtle abstract geometric shapes -->
                    <path d="M120 80 L160 40 L200 80 L160 120 Z" stroke="#EAF5E8" stroke-width="2" />
                    <circle cx="160" cy="80" r="16" stroke="#F1C40F" stroke-width="1.5" />
                    <path d="M480 60 C480 90, 520 90, 520 120" stroke="#EAF5E8" stroke-width="2" />
                    <path d="M820 100 Q860 60, 900 100 T980 100" stroke="#2ECC71" stroke-width="2" />
                    <rect x="750" y="50" width="40" height="40" rx="8" stroke="#EAF5E8" stroke-width="1.5" />
                    <path d="M60 260 L90 230 L120 260" stroke="#F1C40F" stroke-width="2" />
                </svg>
            </div>

            <!-- Ground Skyline & Futsal Court Elements (Anchors the mascot to the container floor) -->
            <div class="pointer-events-none absolute inset-x-0 bottom-0 z-0 h-28 sm:h-36 lg:h-44" aria-hidden="true">
                <svg class="h-full w-full" viewBox="0 0 1440 180" preserveAspectRatio="none" fill="none">
                    <!-- Ground baseline -->
                    <path d="M0 178 H1440" stroke="#EAF5E8" stroke-opacity="0.3" stroke-width="3" />

                    <!-- Stylized architectural & futsal arena outlines (harmonious Garuda colors) -->
                    <!-- Left Section: School & Arena Silhouette -->
                    <path d="M20 178 V135 H65 V115 H95 V135 H140 V178" stroke="#F1C40F" stroke-opacity="0.65" stroke-width="2.5" fill="rgba(241, 196, 15, 0.08)" />
                    <path d="M155 178 V145 H185 V125 H210 V145 H240 V178" stroke="#2ECC71" stroke-opacity="0.6" stroke-width="2.5" fill="rgba(46, 204, 113, 0.08)" />
                    <path d="M260 178 V150 H300 V130 H330 V178" stroke="#EAF5E8" stroke-opacity="0.4" stroke-width="2" />

                    <!-- Center Section: Futsal Center Court Line & Penalty Box Arc -->
                    <path d="M420 178 V140 H740 V178" stroke="#EAF5E8" stroke-opacity="0.35" stroke-width="2.5" />
                    <circle cx="580" cy="178" r="38" stroke="#F1C40F" stroke-opacity="0.5" stroke-width="2" />
                    <path d="M540 178 V155 H620 V178" stroke="#2ECC71" stroke-opacity="0.6" stroke-width="2" fill="rgba(46, 204, 113, 0.06)" />

                    <!-- Right Section: Arena Gate & Grandstand behind Mascot -->
                    <path d="M880 178 V142 H930 V122 H970 V142 H1020 V178" stroke="#2ECC71" stroke-opacity="0.4" stroke-width="2" />
                    <path d="M1040 178 V130 H1090 V110 H1130 V130 H1180 V178" stroke="#F1C40F" stroke-opacity="0.45" stroke-width="2" />
                    <path d="M1200 178 V148 H1240 V128 H1280 V148 H1340 V178" stroke="#EAF5E8" stroke-opacity="0.35" stroke-width="2" />
                    <path d="M1360 178 V138 H1410 V178" stroke="#2ECC71" stroke-opacity="0.4" stroke-width="2" />
                </svg>
            </div>

            <!-- Hero Main Grid -->
            <div class="relative z-10 grid grid-cols-1 items-end gap-6 px-6 pt-10 sm:px-10 sm:pt-14 lg:min-h-[540px] lg:grid-cols-12 lg:px-14 lg:pt-14 lg:pb-0">
                <!-- Left Column: Copywriting & Actions -->
                <div class="space-y-6 pb-6 text-center lg:col-span-7 lg:pb-14 lg:text-left">
                    <!-- Top Pill Badge (ITB-style Date & Category) -->
                    <div class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2 backdrop-blur-md">
                        <span class="text-sm">📅</span>
                        <span class="font-heading text-xs font-bold uppercase tracking-wider text-garuda-gold">
                            2026 Series · SMP/MTs Sederajat Se-Jawa Barat
                        </span>
                    </div>

                    <!-- Main Bold Headline -->
                    <h1 class="font-display text-5xl uppercase leading-[0.92] tracking-wide text-garuda-white sm:text-6xl md:text-7xl">
                        Buktikan Nyali,<br>
                        <span class="text-garuda-gold">Rebut Tahta Juara!</span>
                    </h1>

                    <!-- Subtitle / Context -->
                    <div class="space-y-1">
                        <p class="font-heading text-sm font-semibold text-garuda-mint sm:text-base">
                            SMEAN Cup 2026 Futsal Championship
                        </p>
                        <p class="mx-auto max-w-xl text-sm leading-relaxed text-emerald-50/90 sm:text-base lg:mx-0">
                            Garuda Arena — Sasana Olahraga SMKN 1 Garut, Jawa Barat.<br class="hidden sm:inline">
                            Ajang futsal paling bergengsi untuk talenta muda terbaik.
                        </p>
                    </div>

                    <!-- CTA Action Buttons (ITB-style Pill Buttons with Diagonal Arrow) -->
                    <div class="flex flex-col items-center justify-center gap-3 pt-2 sm:flex-row lg:justify-start">
                        @guest
                            <a
                                href="{{ route('register') }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-garuda-gold px-8 py-3.5 font-heading text-xs font-extrabold uppercase tracking-wider text-garuda-obsidian shadow-lg shadow-amber-500/20 transition-all hover:bg-yellow-300 hover:shadow-xl sm:w-auto"
                            >
                                <span>Daftar Tim Sekarang</span>
                                <span class="text-base font-bold leading-none">↗</span>
                            </a>
                        @else
                            <a
                                href="{{ route('dashboard') }}"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-garuda-gold px-8 py-3.5 font-heading text-xs font-extrabold uppercase tracking-wider text-garuda-obsidian shadow-lg shadow-amber-500/20 transition-all hover:bg-yellow-300 hover:shadow-xl sm:w-auto"
                            >
                                <span>Dashboard Tim</span>
                                <span class="text-base font-bold leading-none">↗</span>
                            </a>
                        @endguest

                        <a
                            href="{{ route('public.schedule') }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-white/60 bg-white/10 px-8 py-3.5 font-heading text-xs font-bold uppercase tracking-wider text-white backdrop-blur-sm transition-all hover:bg-white hover:text-garuda-obsidian sm:w-auto"
                        >
                            <span>Jadwal &amp; Live Score</span>
                            <span class="text-base font-bold leading-none">↗</span>
                        </a>
                    </div>

                    <!-- Quick Metrics Counter (Subtle Glassmorphic Bar) -->
                    <div class="mx-auto grid max-w-lg grid-cols-3 gap-4 border-t border-white/15 pt-6 text-center sm:text-left lg:mx-0">
                        <div>
                            <span class="block font-display text-3xl text-garuda-gold">{{ $teams->count() }}</span>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-100/80">Tim Terdaftar</span>
                        </div>
                        <div>
                            <span class="block font-display text-3xl text-garuda-white">64+ TIM</span>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-100/80">Target Kuota</span>
                        </div>
                        <div>
                            <span class="block font-display text-3xl text-garuda-white">GARUDA ARENA</span>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-emerald-100/80">Venue SMKN 1</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Mascot Gatam (Flushed to bottom edge, ITB Open House style) -->
                <div class="relative flex items-end justify-center self-end pt-4 lg:col-span-5 lg:h-full lg:justify-end lg:pt-0">
                    <img
                        src="{{ asset('images/mascot-gatam.png') }}"
                        alt="Maskot Resmi Gatam SMEAN Cup SMKN 1 Garut"
                        class="relative z-10 max-h-[380px] w-auto object-contain object-bottom drop-shadow-[0_20px_25px_rgba(0,0,0,0.35)] sm:max-h-[460px] lg:max-h-[510px] xl:max-h-[540px]"
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
