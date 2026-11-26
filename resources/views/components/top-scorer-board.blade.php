@props(['topScorers'])

<section {{ $attributes->merge(['class' => 'scroll-mt-24 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8']) }}>
    <div class="mb-6 flex flex-col justify-between gap-4 border-b border-gray-100 pb-5 sm:flex-row sm:items-end">
        <div>
            <p class="font-heading text-xs font-semibold uppercase tracking-widest text-garuda-pitch">Statistik Turnamen</p>
            <h2 class="mt-1 font-display text-3xl uppercase tracking-wide text-garuda-obsidian sm:text-4xl">
                Statistik &amp; <span class="text-garuda-gold">Top Scorer</span>
            </h2>
            <p class="mt-1 text-sm text-gray-500">Daftar pencetak gol terbanyak SMEAN Cup 2026 (Peringkat #1 – #10).</p>
        </div>
        <div class="inline-flex items-center gap-2 self-start rounded-full border border-garuda-gold/40 bg-garuda-obsidian px-4 py-1.5 sm:self-auto">
            <span class="h-2 w-2 rounded-full bg-garuda-gold" aria-hidden="true"></span>
            <span class="font-heading text-[11px] font-bold uppercase tracking-wider text-garuda-gold">Top 10 Leaderboard</span>
        </div>
    </div>

    @if ($topScorers->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50/70 px-6 py-12 text-center">
            <p class="font-heading text-sm font-bold uppercase tracking-wide text-garuda-obsidian">Belum Ada Gol Tercatat</p>
            <p class="mt-1 text-xs text-gray-500">Papan peringkat Top Scorer akan terisi otomatis setelah pertandingan berlangsung.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-gray-200">
            <table class="w-full border-collapse text-left font-sans text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-garuda-obsidian font-heading text-[11px] uppercase tracking-wider text-garuda-white">
                        <th scope="col" class="px-4 py-3.5 text-center">Peringkat</th>
                        <th scope="col" class="px-4 py-3.5">Nama Pemain</th>
                        <th scope="col" class="px-4 py-3.5 text-center">Nomor Punggung</th>
                        <th scope="col" class="px-4 py-3.5">Nama Sekolah / Tim</th>
                        <th scope="col" class="px-4 py-3.5 text-center">Jumlah Gol</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($topScorers as $index => $player)
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
                                        <x-category-badge :kategori="$player->team->kategori ?? 'putra'" />
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-baseline gap-1 rounded-full px-3.5 py-1 {{ $isFirst ? 'bg-garuda-gold/25 text-amber-900' : 'bg-emerald-50 text-garuda-pitch' }}">
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
</section>
