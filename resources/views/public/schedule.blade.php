@extends('layouts.app')

@section('title', 'Jadwal & Live Score — SMEAN Cup 2026')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
        <div class="mb-8 flex flex-col gap-5 border-b border-gray-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="font-heading text-xs font-bold uppercase tracking-widest text-garuda-pitch">Real-Time Match Center</p>
                <h1 class="mt-2 font-display text-4xl uppercase tracking-wide text-garuda-obsidian sm:text-5xl">
                    Jadwal &amp; <span class="text-garuda-gold">Live Score</span>
                </h1>
                <p class="mt-2 text-sm text-gray-600">Pantau jadwal pertandingan dan skor terbaru SMEAN Cup.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500">Pembaruan otomatis</span>
                <span id="status-indicator" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800" role="status" aria-live="polite">
                    Aktif (5s)
                </span>
            </div>
        </div>

        <div id="matches-container" class="grid grid-cols-1 gap-5 md:grid-cols-2" aria-busy="true">
            <div class="col-span-full rounded-2xl border border-gray-200 bg-white py-16 text-center text-sm text-gray-500 shadow-sm">
                Memuat data pertandingan terbaru...
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('matches-container');
            const statusIndicator = document.getElementById('status-indicator');
            let hasLoadedData = false;
            let requestPending = false;

            function createElement(tag, className, text) {
                const element = document.createElement(tag);
                element.className = className;
                if (text !== undefined) {
                    element.textContent = text;
                }
                return element;
            }

            function createTeamSummary(team, fallback, alignment) {
                const wrapper = createElement('div', `col-span-4 text-center ${alignment}`);
                wrapper.append(
                    createElement('h2', 'truncate font-heading text-sm font-bold text-garuda-obsidian sm:text-base', team?.nama_tim || fallback),
                    createElement('p', 'truncate text-xs text-gray-500', team?.asal_sekolah || ''),
                );
                return wrapper;
            }

            function createMatchCard(match) {
                const card = createElement('article', 'rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-gray-300');
                card.dataset.status = match.status_match;

                const top = createElement('div', 'mb-4 flex items-center justify-between gap-3 text-xs text-gray-500');
                top.append(createElement('span', 'rounded-md bg-gray-100 px-3 py-1 text-gray-700', match.lapangan || 'Garuda Arena'));

                let badgeClass = 'rounded-full bg-amber-100 px-3 py-1 font-semibold uppercase tracking-wide text-amber-800';
                let badgeText = 'AKAN DATANG';
                if (match.status_match === 'live') {
                    badgeClass = 'rounded-full bg-red-100 px-3 py-1 font-semibold uppercase tracking-wide text-red-800';
                    badgeText = 'LIVE';
                } else if (match.status_match === 'finished') {
                    badgeClass = 'rounded-full bg-emerald-100 px-3 py-1 font-semibold uppercase tracking-wide text-emerald-800';
                    badgeText = 'SELESAI';
                }
                top.append(createElement('span', badgeClass, badgeText));

                const teams = createElement('div', 'my-5 grid grid-cols-9 items-center gap-2');
                const score = createElement('div', 'col-span-1 rounded-lg bg-garuda-obsidian px-1 py-2 text-center font-display text-2xl text-garuda-gold sm:text-3xl', `${match.skor_home ?? 0} : ${match.skor_away ?? 0}`);
                teams.append(
                    createTeamSummary(match.home_team, 'Tim Home', 'sm:text-right'),
                    score,
                    createTeamSummary(match.away_team, 'Tim Away', 'sm:text-left'),
                );

                const details = createElement('div', 'flex items-center justify-between gap-3 border-t border-gray-100 pt-4 text-xs text-gray-500');
                const round = createElement('span', '', 'Babak: ');
                round.append(createElement('strong', 'font-semibold uppercase text-garuda-obsidian', match.babak || 'Grup'));
                const time = createElement('span', '', 'Waktu: ');
                time.append(createElement('strong', 'font-semibold text-garuda-obsidian', match.waktu_tanding || '-'));
                details.append(round, time);

                card.append(top, teams, details);
                return card;
            }

            function showMessage(message) {
                const emptyState = createElement('div', 'col-span-full rounded-2xl border border-gray-200 bg-white py-16 text-center shadow-sm');
                emptyState.append(createElement('p', 'text-sm text-gray-500', message));
                container.replaceChildren(emptyState);
            }

            async function fetchLiveScores() {
                if (requestPending) {
                    return;
                }
                requestPending = true;

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

                    if (result.data.length === 0) {
                        showMessage('Belum ada jadwal pertandingan yang tersedia saat ini.');
                    } else {
                        container.replaceChildren(...result.data.map(createMatchCard));
                    }

                    hasLoadedData = true;
                    container.setAttribute('aria-busy', 'false');
                    statusIndicator.className = 'inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800';
                    if (statusIndicator.textContent !== 'Aktif (5s)') {
                        statusIndicator.textContent = 'Aktif (5s)';
                    }
                } catch (error) {
                    console.error('Gagal mengambil data live score:', error);
                    statusIndicator.className = 'inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-800';
                    if (statusIndicator.textContent !== 'Koneksi terputus') {
                        statusIndicator.textContent = 'Koneksi terputus';
                    }
                    if (!hasLoadedData) {
                        showMessage('Data pertandingan belum dapat dimuat. Sistem akan mencoba kembali.');
                    }
                    container.setAttribute('aria-busy', 'false');
                } finally {
                    requestPending = false;
                }
            }

            fetchLiveScores();
            window.setInterval(fetchLiveScores, 5000);
        });
    </script>
@endpush
