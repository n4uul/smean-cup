@props([
    'topScorersPutra' => collect(),
    'topScorersPutri' => collect(),
])

@php
    $leaderboards = [
        'putra' => [
            'label' => 'Putra',
            'items' => $topScorersPutra,
            'tabActive' => 'bg-gradient-to-r from-garuda-pitch to-cyan-700 text-white shadow-sm',
            'badgeClass' => 'border-cyan-200 bg-cyan-50 text-cyan-800',
            'dotClass' => 'bg-cyan-500',
            'headerAccent' => 'border-b-2 border-cyan-500/70',
        ],
        'putri' => [
            'label' => 'Putri',
            'items' => $topScorersPutri,
            'tabActive' => 'bg-gradient-to-r from-rose-600 to-amber-500 text-white shadow-sm',
            'badgeClass' => 'border-rose-200 bg-rose-50 text-rose-700',
            'dotClass' => 'bg-rose-500',
            'headerAccent' => 'border-b-2 border-rose-500/70',
        ],
    ];
@endphp

<section
    id="top-scorer"
    x-data="{ activeTab: 'putra' }"
    {{ $attributes->merge(['class' => 'scroll-mt-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8']) }}
>
    <div class="mb-6 flex flex-col justify-between gap-4 border-b border-gray-100 pb-5 lg:flex-row lg:items-end">
        <div>
            <p class="font-heading text-xs font-semibold uppercase tracking-widest text-garuda-pitch">Statistik Turnamen</p>
            <h2 class="mt-1 font-display text-3xl uppercase tracking-wide text-garuda-obsidian sm:text-4xl">
                Statistik &amp; <span class="text-garuda-gold">Top Scorer</span>
            </h2>
            <p class="mt-1 text-sm text-gray-500">Daftar pencetak gol terbanyak SMEAN Cup 2026 per kategori.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="inline-flex rounded-xl border border-gray-200 bg-gray-50 p-1" role="tablist" aria-label="Pilih kategori Top Scorer">
                <button
                    type="button"
                    role="tab"
                    @click="activeTab = 'putra'"
                    :aria-selected="(activeTab === 'putra').toString()"
                    :class="activeTab === 'putra' ? '{{ $leaderboards['putra']['tabActive'] }}' : 'text-gray-600 hover:text-garuda-obsidian'"
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 font-heading text-xs font-bold uppercase tracking-wide transition"
                >
                    <span class="h-2 w-2 rounded-full" :class="activeTab === 'putra' ? 'bg-cyan-300' : 'bg-cyan-500'" aria-hidden="true"></span>
                    Top Scorer Putra
                </button>
                <button
                    type="button"
                    role="tab"
                    @click="activeTab = 'putri'"
                    :aria-selected="(activeTab === 'putri').toString()"
                    :class="activeTab === 'putri' ? '{{ $leaderboards['putri']['tabActive'] }}' : 'text-gray-600 hover:text-garuda-obsidian'"
                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2 font-heading text-xs font-bold uppercase tracking-wide transition"
                >
                    <span class="h-2 w-2 rounded-full" :class="activeTab === 'putri' ? 'bg-amber-300' : 'bg-rose-500'" aria-hidden="true"></span>
                    Top Scorer Putri
                </button>
            </div>
        </div>
    </div>

    @foreach ($leaderboards as $key => $board)
        <div
            x-show="activeTab === '{{ $key }}'"
            @if ($key !== 'putra') x-cloak @endif
            role="tabpanel"
            aria-label="Top Scorer {{ $board['label'] }}"
        >
            <div class="mb-4 flex items-center justify-between gap-2">
                <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 font-heading text-[11px] font-bold uppercase tracking-wider {{ $board['badgeClass'] }}">
                    <span class="h-2 w-2 rounded-full {{ $board['dotClass'] }}" aria-hidden="true"></span>
                    Kategori {{ $board['label'] }}
                </span>
                <span class="text-xs text-gray-500">Top 10 Leaderboard</span>
            </div>

            @if ($board['items']->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50/70 px-6 py-12 text-center">
                    <p class="font-heading text-sm font-bold uppercase tracking-wide text-garuda-obsidian">
                        Belum ada gol tercatat untuk kategori {{ $board['label'] }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Papan peringkat Top Scorer {{ $board['label'] }} akan terisi otomatis setelah pertandingan berlangsung.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full border-collapse text-left font-sans text-sm">
                        <thead>
                            <tr class="{{ $board['headerAccent'] }} bg-garuda-obsidian font-heading text-[11px] uppercase tracking-wider text-garuda-white">
                                <th scope="col" class="px-4 py-3.5 text-center">Peringkat</th>
                                <th scope="col" class="px-4 py-3.5">Nama Lengkap Pemain</th>
                                <th scope="col" class="px-4 py-3.5 text-center">No. Punggung</th>
                                <th scope="col" class="px-4 py-3.5">Asal Sekolah / Tim</th>
                                <th scope="col" class="px-4 py-3.5 text-center">Jumlah Gol</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($board['items'] as $index => $player)
                                @php
                                    $rank = $index + 1;
                                    $isFirst = $rank === 1;
                                    $rowClass = $isFirst
                                        ? 'bg-gradient-to-r from-amber-50 via-yellow-50/80 to-white'
                                        : 'bg-white hover:bg-gray-50/80';
                                    $rankBadgeClass = match ($rank) {
                                        1 => 'bg-garuda-gold text-garuda-obsidian shadow-sm ring-2 ring-amber-300/60',
                                        2 => 'bg-gray-200 text-garuda-obsidian',
                                        3 => 'bg-amber-100 text-amber-900',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <tr class="transition {{ $rowClass }}">
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex min-w-[2.5rem] items-center justify-center gap-1 rounded-full px-3 py-1 font-display text-base tracking-wider {{ $rankBadgeClass }}">
                                            @if ($isFirst)
                                                <span aria-hidden="true">👑</span>
                                            @endif
                                            #{{ $rank }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex flex-col">
                                            <span class="font-heading text-sm font-bold text-garuda-obsidian">{{ $player->nama_lengkap }}</span>
                                            <span class="text-xs text-gray-500">{{ $player->posisi }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex min-w-[2.5rem] items-center justify-center rounded-full bg-garuda-obsidian px-3 py-1 font-display text-sm tracking-wider text-garuda-gold shadow-sm">
                                            #{{ $player->nomor_punggung }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <div>
                                                <p class="font-heading text-xs font-bold uppercase tracking-wide text-garuda-obsidian">
                                                    {{ $player->team?->nama_tim ?? '-' }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $player->team?->asal_sekolah ?? '-' }}</p>
                                            </div>
                                            @if ($player->team)
                                                <x-category-badge :kategori="$player->team->kategori ?? $key" />
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200/80 bg-amber-50/90 px-3.5 py-1 text-amber-900">
                                            <svg class="h-4 w-4 shrink-0 text-amber-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 3.3l1.35-.95c1.82.56 3.37 1.76 4.38 3.34l-.39 1.34-1.35.46L14 7.7V5.3zm-3.35-.95L11 5.3v2.4L8.01 9.49l-1.35-.46-.39-1.34c1.01-1.58 2.56-2.78 4.38-3.34zM7.08 17.11l-1.14.1C4.73 15.81 4 13.99 4 12c0-.12.01-.23.02-.35l1-.73 1.38.48 1.46 4.34-.78 1.37zm7.42 2.48c-.79.26-1.63.41-2.5.41s-1.71-.15-2.5-.41l-.69-1.49.64-1.1h5.1l.64 1.1-.69 1.49zM14.27 15H9.73l-1.35-4.02L12 8.44l3.63 2.54L14.27 15zm3.79 2.21l-1.14-.1-.79-1.37 1.46-4.34 1.39-.47 1 .73c.01.11.02.22.02.34 0 1.99-.73 3.81-1.94 5.21z"/>
                                            </svg>
                                            <span class="font-display text-2xl leading-none">{{ $player->jumlah_gol }}</span>
                                            <span class="font-heading text-[10px] font-bold uppercase tracking-wider">Gol</span>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endforeach
</section>
