@extends('layouts.app')

@section('title', 'Jadwal & Live Score — SMEAN Cup 2026')

@section('content')
    <section x-data="{ activeTab: 'matches', category: 'all' }" class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
        <div class="mb-6 flex flex-col gap-5 border-b border-gray-200 pb-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-heading text-xs font-bold uppercase tracking-widest text-garuda-pitch">Real-Time Match Center</p>
                <h1 class="mt-2 font-display text-4xl uppercase tracking-wide text-garuda-obsidian sm:text-5xl">
                    Jadwal &amp; <span class="text-garuda-gold">Live Score</span>
                </h1>
                <p class="mt-2 text-sm text-gray-600">Pantau jadwal pertandingan, skor terbaru, dan papan Top Scorer SMEAN Cup.</p>
            </div>
            <div x-show="activeTab === 'matches'" class="flex flex-wrap items-center justify-between gap-4 sm:justify-end">
                <div class="inline-flex rounded-xl border border-gray-200 bg-white p-1 shadow-sm" role="group" aria-label="Filter kategori pertandingan">
                    <button
                        type="button"
                        @click="category = 'all'; window.dispatchEvent(new CustomEvent('schedule-category-change', { detail: 'all' }))"
                        :aria-pressed="(category === 'all').toString()"
                        :class="category === 'all' ? 'bg-garuda-obsidian text-white shadow-sm' : 'text-gray-600 hover:text-garuda-obsidian'"
                        class="rounded-lg px-3.5 py-1.5 font-heading text-xs font-bold uppercase tracking-wide transition"
                    >
                        Semua
                    </button>
                    <button
                        type="button"
                        @click="category = 'putra'; window.dispatchEvent(new CustomEvent('schedule-category-change', { detail: 'putra' }))"
                        :aria-pressed="(category === 'putra').toString()"
                        :class="category === 'putra' ? 'bg-sky-600 text-white shadow-sm' : 'text-gray-600 hover:text-garuda-obsidian'"
                        class="rounded-lg px-3.5 py-1.5 font-heading text-xs font-bold uppercase tracking-wide transition"
                    >
                        Putra
                    </button>
                    <button
                        type="button"
                        @click="category = 'putri'; window.dispatchEvent(new CustomEvent('schedule-category-change', { detail: 'putri' }))"
                        :aria-pressed="(category === 'putri').toString()"
                        :class="category === 'putri' ? 'bg-rose-600 text-white shadow-sm' : 'text-gray-600 hover:text-garuda-obsidian'"
                        class="rounded-lg px-3.5 py-1.5 font-heading text-xs font-bold uppercase tracking-wide transition"
                    >
                        Putri
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500">Pembaruan otomatis</span>
                    <span id="status-indicator" class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800" role="status" aria-live="polite">
                        Aktif (5s)
                    </span>
                </div>
            </div>
        </div>

        <div class="mb-8 inline-flex rounded-xl border border-gray-200 bg-white p-1.5 shadow-sm" role="tablist" aria-label="Navigasi Jadwal dan Statistik">
            <button
                type="button"
                role="tab"
                @click="activeTab = 'matches'"
                :aria-selected="(activeTab === 'matches').toString()"
                :class="activeTab === 'matches' ? 'bg-garuda-obsidian text-garuda-gold shadow-sm' : 'text-gray-600 hover:text-garuda-obsidian'"
                class="rounded-lg px-4 py-2 font-heading text-xs font-bold uppercase tracking-wider transition"
            >
                Jadwal &amp; Live Score
            </button>
            <button
                type="button"
                role="tab"
                @click="activeTab = 'top-scorer'"
                :aria-selected="(activeTab === 'top-scorer').toString()"
                :class="activeTab === 'top-scorer' ? 'bg-garuda-obsidian text-garuda-gold shadow-sm' : 'text-gray-600 hover:text-garuda-obsidian'"
                class="rounded-lg px-4 py-2 font-heading text-xs font-bold uppercase tracking-wider transition"
            >
                Statistik &amp; Top Scorer
            </button>
        </div>

        <div x-show="activeTab === 'matches'">
            <div id="matches-container" class="grid grid-cols-1 gap-5 md:grid-cols-2" aria-busy="true">
                <div class="col-span-full rounded-2xl border border-gray-200 bg-white py-16 text-center text-sm text-gray-500 shadow-sm">
                    Memuat data pertandingan terbaru...
                </div>
            </div>

            <div id="empty-filter-state" class="hidden rounded-2xl border border-gray-200 bg-white py-16 text-center text-sm text-gray-500 shadow-sm">
                Belum ada jadwal pertandingan untuk kategori ini.
            </div>
        </div>

        <div x-show="activeTab === 'top-scorer'" x-cloak>
            <x-top-scorer-board :top-scorers="$topScorers" />
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('matches-container');
            const emptyFilterState = document.getElementById('empty-filter-state');
            const statusIndicator = document.getElementById('status-indicator');
            let hasLoadedData = false;
            let requestPending = false;
            let activeCategory = 'all';

            function createElement(tag, className, text) {
                const element = document.createElement(tag);
                element.className = className;
                if (text !== undefined) {
                    element.textContent = text;
                }
                return element;
            }

            function categoryPresentation(kategori) {
                if (kategori === 'putri') {
                    return {
                        className: 'inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-2.5 py-0.5 font-heading text-[10px] font-bold uppercase tracking-wider text-rose-700',
                        label: 'Putri',
                    };
                }

                return {
                    className: 'inline-flex items-center rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 font-heading text-[10px] font-bold uppercase tracking-wider text-sky-700',
                    label: 'Putra',
                };
            }

            function formatMatchTime(value) {
                if (!value) {
                    return '-';
                }
                const normalized = String(value).replace(' ', 'T');
                const date = new Date(normalized);
                if (Number.isNaN(date.getTime())) {
                    return String(value);
                }
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const day = String(date.getDate()).padStart(2, '0');
                const month = months[date.getMonth()];
                const year = date.getFullYear();
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${day} ${month} ${year} · ${hours}:${minutes} WIB`;
            }

            function applyCategoryFilter() {
                const cards = Array.from(container.querySelectorAll('article[data-match-id]'));
                if (cards.length === 0) {
                    emptyFilterState.classList.add('hidden');
                    return;
                }

                let visibleCount = 0;
                for (const card of cards) {
                    const matchesFilter = activeCategory === 'all' || card.dataset.category === activeCategory;
                    card.classList.toggle('hidden', !matchesFilter);
                    if (matchesFilter) {
                        visibleCount += 1;
                    }
                }

                emptyFilterState.classList.toggle('hidden', visibleCount > 0);
            }

            window.addEventListener('schedule-category-change', (event) => {
                activeCategory = event.detail || 'all';
                applyCategoryFilter();
            });

            function matchStatusPresentation(status) {
                if (status === 'live') {
                    return {
                        className: 'rounded-full bg-red-100 px-3 py-1 font-semibold uppercase tracking-wide text-red-800',
                        label: 'LIVE',
                    };
                }
                if (status === 'finished') {
                    return {
                        className: 'rounded-full bg-emerald-100 px-3 py-1 font-semibold uppercase tracking-wide text-emerald-800',
                        label: 'SELESAI',
                    };
                }

                return {
                    className: 'rounded-full bg-amber-100 px-3 py-1 font-semibold uppercase tracking-wide text-amber-800',
                    label: 'AKAN DATANG',
                };
            }

            function createTeamSummary(team, fallback, alignment, side) {
                const wrapper = createElement('div', `col-span-4 text-center ${alignment}`);
                const name = createElement('h2', 'truncate font-heading text-sm font-bold text-garuda-obsidian sm:text-base', team?.nama_tim || fallback);
                const school = createElement('p', 'truncate text-xs text-gray-500', team?.asal_sekolah || '');
                name.dataset.teamName = side;
                school.dataset.teamSchool = side;
                wrapper.append(
                    name,
                    school,
                );
                return wrapper;
            }

            function updateHypeDisplay(card, homeCount, awayCount) {
                const safeHomeCount = Math.max(0, Number(homeCount) || 0);
                const safeAwayCount = Math.max(0, Number(awayCount) || 0);
                const totalCount = safeHomeCount + safeAwayCount;
                const homePercent = totalCount === 0 ? 0 : Math.round((safeHomeCount / totalCount) * 100);
                const awayPercent = totalCount === 0 ? 0 : 100 - homePercent;
                const homeName = card.querySelector('[data-team-name="home"]').textContent;
                const awayName = card.querySelector('[data-team-name="away"]').textContent;
                const progress = card.querySelector('[data-hype-progress]');

                card.querySelector('[data-hype-bar="home"]').style.width = `${homePercent}%`;
                card.querySelector('[data-hype-bar="away"]').style.width = `${awayPercent}%`;
                card.querySelector('[data-hype-percent="home"]').textContent = `${homeName} ${homePercent}%`;
                card.querySelector('[data-hype-percent="away"]').textContent = `${awayPercent}% ${awayName}`;
                card.querySelector('[data-hype-total]').textContent = `${totalCount} dukungan`;
                progress.setAttribute('aria-valuenow', String(homePercent));
                progress.setAttribute(
                    'aria-label',
                    `${homeName}: ${homePercent}% dukungan; ${awayName}: ${awayPercent}% dukungan`,
                );

                for (const countElement of card.querySelectorAll('[data-hype-count="home"]')) {
                    countElement.textContent = String(safeHomeCount);
                }
                for (const countElement of card.querySelectorAll('[data-hype-count="away"]')) {
                    countElement.textContent = String(safeAwayCount);
                }
                card.querySelector('button[data-hype-side="home"]').setAttribute(
                    'aria-label',
                    `Dukung ${homeName}. ${safeHomeCount} dukungan.`,
                );
                card.querySelector('button[data-hype-side="away"]').setAttribute(
                    'aria-label',
                    `Dukung ${awayName}. ${safeAwayCount} dukungan.`,
                );
            }

            function createHypeButton(match, side, teamName, count) {
                const colorClass = side === 'home'
                    ? 'bg-garuda-mint hover:bg-emerald-400'
                    : 'bg-garuda-gold hover:bg-yellow-300';
                const button = createElement(
                    'button',
                    `hype-vote-button flex min-w-0 items-center justify-between gap-2 rounded-xl ${colorClass} px-3 py-2.5 text-left font-heading text-xs font-bold uppercase text-garuda-obsidian shadow-sm hover:shadow-md disabled:cursor-wait disabled:opacity-70 sm:text-[11px]`,
                );
                const label = createElement('span', 'truncate', `🔥 Dukung ${teamName}`);
                const counter = createElement('span', 'inline-flex min-w-8 shrink-0 items-center justify-center rounded-full bg-black/10 px-2 py-1 text-xs tabular-nums', String(count));

                button.type = 'button';
                button.dataset.matchId = String(match.id);
                button.dataset.hypeSide = side;
                button.setAttribute('aria-label', `Dukung ${teamName}. ${count} dukungan.`);
                counter.dataset.hypeCount = side;
                button.append(label, counter);

                return button;
            }

            function createHypeSection(match) {
                const homeName = match.home_team?.nama_tim || 'Tim Home';
                const awayName = match.away_team?.nama_tim || 'Tim Away';
                const section = createElement('section', 'mt-4 border-t border-gray-100 pt-4');
                const heading = createElement('div', 'mb-3 flex items-center justify-between gap-2');
                const title = createElement('h3', 'font-heading text-xs font-bold uppercase tracking-wide text-garuda-obsidian', 'Dukungan Suporter');
                const total = createElement('span', 'text-xs text-gray-500', '0 dukungan');
                total.dataset.hypeTotal = '';
                heading.append(title, total);

                const percentages = createElement('div', 'mb-1 flex items-center justify-between gap-2 text-xs font-semibold');
                const homePercentage = createElement('span', 'min-w-0 truncate text-garuda-pitch', `${homeName} 0%`);
                const awayPercentage = createElement('span', 'min-w-0 truncate text-right text-amber-700', `0% ${awayName}`);
                homePercentage.dataset.hypePercent = 'home';
                awayPercentage.dataset.hypePercent = 'away';
                percentages.append(homePercentage, awayPercentage);

                const progress = createElement('div', 'flex h-3 overflow-hidden rounded-full bg-gray-100');
                const homeBar = createElement('div', 'hype-progress-fill h-full bg-garuda-mint');
                const awayBar = createElement('div', 'hype-progress-fill h-full bg-garuda-gold');
                progress.dataset.hypeProgress = '';
                progress.setAttribute('role', 'meter');
                progress.setAttribute('aria-valuemin', '0');
                progress.setAttribute('aria-valuemax', '100');
                progress.setAttribute('aria-valuenow', '0');
                homeBar.dataset.hypeBar = 'home';
                awayBar.dataset.hypeBar = 'away';
                progress.append(homeBar, awayBar);

                const buttons = createElement('div', 'mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2');
                buttons.append(
                    createHypeButton(match, 'home', homeName, Number(match.hype_home_count) || 0),
                    createHypeButton(match, 'away', awayName, Number(match.hype_away_count) || 0),
                );

                const status = createElement('p', 'mt-2 min-h-4 text-center text-xs text-gray-500');
                status.dataset.hypeStatus = '';
                status.setAttribute('role', 'status');
                status.setAttribute('aria-live', 'polite');
                section.append(heading, percentages, progress, buttons, status);

                return section;
            }

            function updateMatchCard(card, match) {
                const status = matchStatusPresentation(match.status_match);
                const kategori = match.home_team?.kategori || match.away_team?.kategori || 'putra';
                const catPres = categoryPresentation(kategori);

                card.dataset.status = match.status_match;
                card.dataset.category = kategori;
                card.querySelector('[data-match-status]').className = status.className;
                card.querySelector('[data-match-status]').textContent = status.label;

                const catBadge = card.querySelector('[data-match-category]');
                if (catBadge) {
                    catBadge.className = catPres.className;
                    catBadge.textContent = catPres.label;
                }

                const timeEl = card.querySelector('[data-match-time]');
                if (timeEl) {
                    timeEl.textContent = match.waktu_tanding_formatted || formatMatchTime(match.waktu_tanding);
                }

                card.querySelector('[data-match-score]').textContent = `${match.skor_home ?? 0} : ${match.skor_away ?? 0}`;
                card.querySelector('[data-team-name="home"]').textContent = match.home_team?.nama_tim || 'Tim Home';
                card.querySelector('[data-team-school="home"]').textContent = match.home_team?.asal_sekolah || '';
                card.querySelector('[data-team-name="away"]').textContent = match.away_team?.nama_tim || 'Tim Away';
                card.querySelector('[data-team-school="away"]').textContent = match.away_team?.asal_sekolah || '';
                updateHypeDisplay(card, match.hype_home_count, match.hype_away_count);
            }

            function findMatchCard(matchId) {
                return Array.from(container.querySelectorAll('article[data-match-id]'))
                    .find((card) => card.dataset.matchId === String(matchId));
            }

            async function voteHype(matchId, teamSide) {
                const card = findMatchCard(matchId);
                if (!card || card.dataset.hypePending === 'true') {
                    return;
                }

                const status = card.querySelector('[data-hype-status]');
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    status.textContent = 'Token keamanan tidak tersedia. Muat ulang halaman lalu coba lagi.';
                    console.error('Token CSRF untuk vote hype tidak ditemukan.');
                    return;
                }

                card.dataset.hypePending = 'true';
                card.setAttribute('aria-busy', 'true');
                card.querySelectorAll('button[data-hype-side]').forEach((button) => {
                    button.disabled = true;
                });
                status.textContent = 'Mengirim dukungan...';

                try {
                    const response = await fetch(`{{ url('/api/matches') }}/${encodeURIComponent(matchId)}/hype`, {
                        method: 'POST',
                        headers: {
                            Accept: 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({ team_side: teamSide }),
                    });
                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || `Permintaan gagal (${response.status})`);
                    }
                    if (!result.success || !result.data) {
                        throw new Error('Respons vote hype tidak valid.');
                    }

                    const currentCard = findMatchCard(matchId);
                    if (currentCard) {
                        updateHypeDisplay(
                            currentCard,
                            result.data.hype_home_count,
                            result.data.hype_away_count,
                        );
                        currentCard.querySelector('[data-hype-status]').textContent = 'Dukungan terkirim! 🔥';

                        const votedButton = currentCard.querySelector(`button[data-hype-side="${teamSide}"]`);
                        votedButton?.classList.remove('is-cheering');
                        if (votedButton) {
                            void votedButton.offsetWidth;
                            votedButton.classList.add('is-cheering');
                            window.setTimeout(() => votedButton.classList.remove('is-cheering'), 600);
                        }
                    }
                } catch (error) {
                    console.error('Gagal mengirim dukungan suporter:', error);
                    const currentCard = findMatchCard(matchId);
                    if (currentCard) {
                        currentCard.querySelector('[data-hype-status]').textContent = 'Dukungan gagal dikirim. Coba lagi.';
                    }
                } finally {
                    const currentCard = findMatchCard(matchId);
                    if (currentCard) {
                        delete currentCard.dataset.hypePending;
                        currentCard.setAttribute('aria-busy', 'false');
                        currentCard.querySelectorAll('button[data-hype-side]').forEach((button) => {
                            button.disabled = false;
                        });
                    }
                }
            }

            function createMatchCard(match) {
                const kategori = match.home_team?.kategori || match.away_team?.kategori || 'putra';
                const catPres = categoryPresentation(kategori);
                const card = createElement('article', 'rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-gray-300');
                card.dataset.matchId = String(match.id);
                card.dataset.status = match.status_match;
                card.dataset.category = kategori;

                const top = createElement('div', 'mb-4 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500');
                const leftMeta = createElement('div', 'flex flex-wrap items-center gap-2');
                leftMeta.append(createElement('span', 'rounded-md bg-gray-100 px-3 py-1 text-gray-700', match.lapangan || 'Garuda Arena'));
                const catBadge = createElement('span', catPres.className, catPres.label);
                catBadge.dataset.matchCategory = '';
                leftMeta.append(catBadge);

                const status = matchStatusPresentation(match.status_match);
                const statusBadge = createElement('span', status.className, status.label);
                statusBadge.dataset.matchStatus = '';
                top.append(leftMeta, statusBadge);

                const teams = createElement('div', 'my-5 grid grid-cols-9 items-center gap-2');
                const score = createElement('div', 'col-span-1 rounded-lg bg-garuda-obsidian px-1 py-2 text-center font-display text-2xl text-garuda-gold sm:text-3xl', `${match.skor_home ?? 0} : ${match.skor_away ?? 0}`);
                score.dataset.matchScore = '';
                teams.append(
                    createTeamSummary(match.home_team, 'Tim Home', 'sm:text-right', 'home'),
                    score,
                    createTeamSummary(match.away_team, 'Tim Away', 'sm:text-left', 'away'),
                );

                const details = createElement('div', 'flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-4 text-xs text-gray-500');
                const round = createElement('span', '', 'Babak: ');
                round.append(createElement('strong', 'font-semibold uppercase text-garuda-obsidian', match.babak || 'Grup'));
                const time = createElement('span', '', 'Waktu: ');
                const timeValue = createElement('strong', 'font-semibold text-garuda-obsidian', match.waktu_tanding_formatted || formatMatchTime(match.waktu_tanding));
                timeValue.dataset.matchTime = '';
                time.append(timeValue);
                details.append(round, time);

                card.append(top, teams, details, createHypeSection(match));
                updateHypeDisplay(card, match.hype_home_count, match.hype_away_count);
                return card;
            }

            container.addEventListener('click', (event) => {
                if (!(event.target instanceof Element)) {
                    return;
                }

                const button = event.target.closest('button[data-hype-side]');
                const card = button?.closest('article[data-match-id]');
                if (button && card) {
                    voteHype(card.dataset.matchId, button.dataset.hypeSide);
                }
            });

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
                        emptyFilterState.classList.add('hidden');
                    } else {
                        const existingCards = new Map(
                            Array.from(container.querySelectorAll('article[data-match-id]'))
                                .map((card) => [card.dataset.matchId, card]),
                        );
                        const cards = result.data.map((match) => {
                            const existingCard = existingCards.get(String(match.id));
                            if (!existingCard) {
                                return createMatchCard(match);
                            }

                            updateMatchCard(existingCard, match);
                            return existingCard;
                        });
                        container.replaceChildren(...cards);
                        applyCategoryFilter();
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
