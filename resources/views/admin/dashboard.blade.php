<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-10 sm:px-6 lg:px-8">
        <header class="flex flex-col justify-between gap-6 border-b border-gray-200 pb-6 lg:flex-row lg:items-end">
            <div>
                <p class="font-heading text-xs font-bold uppercase tracking-widest text-garuda-pitch">SMEAN Cup 2026</p>
                <h1 class="mt-2 font-display text-4xl uppercase tracking-wide text-garuda-obsidian sm:text-5xl">
                    Dashboard Panitia
                </h1>
                <p class="mt-2 text-sm text-gray-600">Ringkasan pendaftaran tim dan pertandingan.</p>
            </div>
            @include('admin.partials.navigation')
        </header>

        <section aria-labelledby="overview-heading">
            <div class="mb-5">
                <h2 id="overview-heading" class="font-heading text-lg font-bold text-garuda-obsidian">Overview Turnamen</h2>
                <p class="mt-1 text-sm text-gray-500">Pantau status pendaftaran dan jadwal SMEAN Cup.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['label' => 'Total Tim Mendaftar', 'value' => $stats['total_teams'], 'href' => route('admin.teams.index'), 'tone' => 'border-l-garuda-pitch'],
                    ['label' => 'Tim Pending', 'value' => $stats['pending_teams'], 'href' => route('admin.teams.index'), 'tone' => 'border-l-amber-400'],
                    ['label' => 'Tim Approved', 'value' => $stats['approved_teams'], 'href' => route('admin.teams.index'), 'tone' => 'border-l-garuda-mint'],
                    ['label' => 'Total Pertandingan', 'value' => $stats['total_matches'], 'href' => route('admin.matches.index'), 'tone' => 'border-l-garuda-gold'],
                ] as $stat)
                    <a href="{{ $stat['href'] }}" class="rounded-xl border border-gray-200 border-l-4 {{ $stat['tone'] }} bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <span class="block text-sm font-medium text-gray-600">{{ $stat['label'] }}</span>
                        <span class="mt-3 block font-display text-4xl text-garuda-obsidian">{{ $stat['value'] }}</span>
                        <span class="mt-2 block font-heading text-[11px] font-semibold uppercase tracking-wide text-garuda-pitch">Lihat detail</span>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 lg:grid-cols-2" aria-label="Akses cepat">
            <a href="{{ route('admin.teams.index') }}" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-garuda-pitch">
                <p class="font-heading text-xs font-bold uppercase tracking-widest text-garuda-pitch">Pendaftaran</p>
                <h2 class="mt-2 font-display text-3xl uppercase text-garuda-obsidian">Verifikasi Tim</h2>
                <p class="mt-2 text-sm text-gray-600">Periksa profil tim, buka logo dan berkas persyaratan, lalu setujui atau tolak pendaftaran.</p>
            </a>
            <a href="{{ route('admin.matches.index') }}" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-garuda-pitch">
                <p class="font-heading text-xs font-bold uppercase tracking-widest text-garuda-pitch">Pertandingan</p>
                <h2 class="mt-2 font-display text-3xl uppercase text-garuda-obsidian">Jadwal &amp; Live Score</h2>
                <p class="mt-2 text-sm text-gray-600">Buat jadwal pertandingan dan kelola skor serta status secara langsung.</p>
            </a>
        </section>
    </div>
</x-app-layout>
