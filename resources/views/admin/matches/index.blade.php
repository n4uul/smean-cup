<x-app-layout>
    <div
        x-data="{
            showDeleteModal: false,
            deleteUrl: '',
            matchTitle: '',
            openDeleteModal(url, title) {
                this.deleteUrl = url;
                this.matchTitle = title;
                this.showDeleteModal = true;
            },
            closeDeleteModal() {
                this.showDeleteModal = false;
                this.deleteUrl = '';
                this.matchTitle = '';
            }
        }"
        @keydown.escape.window="closeDeleteModal()"
        class="mx-auto max-w-7xl space-y-8 px-4 py-12 sm:px-6 lg:px-8"
    >
        <header class="flex flex-col justify-between gap-6 border-b border-gray-200 pb-6 lg:flex-row lg:items-end">
            <div>
                <p class="font-heading text-xs font-bold uppercase tracking-widest text-garuda-pitch">SMEAN Cup 2026</p>
                <h1 class="mt-2 font-display text-4xl uppercase tracking-wide text-garuda-obsidian sm:text-5xl">
                    Jadwal &amp; Live Score
                </h1>
                <p class="mt-2 text-sm text-gray-600">Buat jadwal dan perbarui skor pertandingan.</p>
            </div>
            @include('admin.partials.navigation')
        </header>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 font-sans text-sm text-emerald-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="mb-2 font-heading text-xl font-bold uppercase tracking-wide text-garuda-obsidian">Tambah Jadwal Pertandingan</h2>
            <p class="mb-6 text-sm text-gray-600">Hanya tim yang sudah disetujui yang dapat dijadwalkan.</p>

            <form action="{{ route('admin.matches.store') }}" method="POST" class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @csrf
                <div>
                    <label for="team_home_id" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-700">Tim Kandang (Home)</label>
                    <select id="team_home_id" name="team_home_id" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-garuda-obsidian focus:border-garuda-pitch focus:outline-none">
                        <option value="">Pilih Tim</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" @selected(old('team_home_id') == $team->id)>{{ $team->nama_tim }} ({{ $team->asal_sekolah }})</option>
                        @endforeach
                    </select>
                    @error('team_home_id') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="team_away_id" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-700">Tim Tandang (Away)</label>
                    <select id="team_away_id" name="team_away_id" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-garuda-obsidian focus:border-garuda-pitch focus:outline-none">
                        <option value="">Pilih Tim</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" @selected(old('team_away_id') == $team->id)>{{ $team->nama_tim }} ({{ $team->asal_sekolah }})</option>
                        @endforeach
                    </select>
                    @error('team_away_id') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="waktu_tanding" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-700">Waktu Tanding</label>
                    <input id="waktu_tanding" type="datetime-local" name="waktu_tanding" value="{{ old('waktu_tanding') }}" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-garuda-obsidian focus:border-garuda-pitch focus:outline-none">
                    @error('waktu_tanding') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="lapangan" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-700">Lapangan</label>
                    <input id="lapangan" type="text" name="lapangan" value="{{ old('lapangan') }}" maxlength="255" placeholder="Contoh: Garuda Arena" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-garuda-obsidian focus:border-garuda-pitch focus:outline-none">
                    @error('lapangan') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="babak" class="mb-2 block font-heading text-xs uppercase tracking-wider text-gray-700">Babak</label>
                    <input id="babak" type="text" name="babak" value="{{ old('babak') }}" maxlength="255" placeholder="Contoh: Penyisihan Grup A" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-garuda-obsidian focus:border-garuda-pitch focus:outline-none">
                    @error('babak') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-xl bg-garuda-mint px-6 py-3 font-heading font-bold uppercase tracking-wider text-garuda-obsidian transition hover:bg-emerald-400">
                        Buat Jadwal
                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-5">
                <h2 class="font-heading text-xl font-bold uppercase tracking-wide text-garuda-obsidian">Kontrol Live Score Pertandingan</h2>
                <p class="mt-1 text-sm text-gray-600">Gunakan tombol +/− untuk menyimpan perubahan skor tanpa memuat ulang halaman.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left font-sans text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 font-heading text-xs uppercase text-gray-500">
                            <th scope="col" class="px-4 py-3">Jadwal &amp; Lapangan</th>
                            <th scope="col" class="px-4 py-3">Tim Bertanding</th>
                            <th scope="col" class="px-4 py-3 text-center">Skor (Home : Away)</th>
                            <th scope="col" class="px-4 py-3">Status Match</th>
                            <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($matches as $match)
                            <tr class="transition hover:bg-gray-50" data-match-row>
                                <td class="px-4 py-4">
                                    <span class="block font-semibold text-garuda-obsidian">{{ $match->waktu_tanding }}</span>
                                    <span class="text-xs text-gray-400">{{ $match->lapangan }} ({{ $match->babak }})</span>
                                </td>
                                <td class="px-4 py-4 font-heading text-garuda-obsidian">
                                    {{ $match->homeTeam->nama_tim }} vs {{ $match->awayTeam->nama_tim }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <form id="match-update-{{ $match->id }}" action="{{ route('admin.matches.update', $match) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="inline-flex items-center gap-2">
                                            <div class="inline-flex items-center gap-1">
                                                <button type="button" data-score-url="{{ route('admin.matches.updateScore', $match) }}" data-score-side="home" data-score-action="decrement" aria-label="Kurangi skor {{ $match->homeTeam->nama_tim }}" class="rounded-md border border-gray-300 px-2 py-1 font-bold text-garuda-obsidian transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-garuda-pitch disabled:opacity-50">-</button>
                                                <input form="match-update-{{ $match->id }}" data-score-field="home" type="number" name="skor_home" value="{{ $match->skor_home }}" min="0" required aria-label="Skor {{ $match->homeTeam->nama_tim }}" class="w-14 rounded-lg border border-gray-300 bg-white py-1 text-center font-display text-lg text-garuda-obsidian focus:border-garuda-pitch focus:outline-none">
                                                <button type="button" data-score-url="{{ route('admin.matches.updateScore', $match) }}" data-score-side="home" data-score-action="increment" aria-label="Tambah skor {{ $match->homeTeam->nama_tim }}" class="rounded-md bg-garuda-mint px-2 py-1 font-bold text-garuda-obsidian transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-garuda-pitch disabled:opacity-50">+</button>
                                            </div>
                                            <span class="font-bold text-gray-400">:</span>
                                            <div class="inline-flex items-center gap-1">
                                                <button type="button" data-score-url="{{ route('admin.matches.updateScore', $match) }}" data-score-side="away" data-score-action="decrement" aria-label="Kurangi skor {{ $match->awayTeam->nama_tim }}" class="rounded-md border border-gray-300 px-2 py-1 font-bold text-garuda-obsidian transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-garuda-pitch disabled:opacity-50">-</button>
                                                <input form="match-update-{{ $match->id }}" data-score-field="away" type="number" name="skor_away" value="{{ $match->skor_away }}" min="0" required aria-label="Skor {{ $match->awayTeam->nama_tim }}" class="w-14 rounded-lg border border-gray-300 bg-white py-1 text-center font-display text-lg text-garuda-obsidian focus:border-garuda-pitch focus:outline-none">
                                                <button type="button" data-score-url="{{ route('admin.matches.updateScore', $match) }}" data-score-side="away" data-score-action="increment" aria-label="Tambah skor {{ $match->awayTeam->nama_tim }}" class="rounded-md bg-garuda-mint px-2 py-1 font-bold text-garuda-obsidian transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-garuda-pitch disabled:opacity-50">+</button>
                                            </div>
                                        </div>
                                        <span class="min-h-4 text-xs text-gray-500" data-score-feedback role="status" aria-live="polite"></span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <label for="status-match-{{ $match->id }}" class="sr-only">Status {{ $match->homeTeam->nama_tim }} vs {{ $match->awayTeam->nama_tim }}</label>
                                    <select id="status-match-{{ $match->id }}" form="match-update-{{ $match->id }}" name="status_match" class="rounded-lg border border-gray-300 bg-white px-2 py-2 text-xs text-garuda-obsidian focus:border-garuda-pitch focus:outline-none">
                                        <option value="upcoming" @selected($match->status_match === 'upcoming')>Akan Datang</option>
                                        <option value="live" @selected($match->status_match === 'live')>Live</option>
                                        <option value="finished" @selected($match->status_match === 'finished')>FT (Selesai)</option>
                                    </select>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button form="match-update-{{ $match->id }}" type="submit" class="rounded-lg border border-garuda-gold bg-white px-4 py-2 font-heading text-xs font-bold uppercase text-garuda-obsidian transition hover:bg-amber-50">
                                            Simpan
                                        </button>
                                        <button
                                            type="button"
                                            @click="openDeleteModal('{{ route('admin.matches.destroy', $match) }}', '{{ addslashes($match->homeTeam->nama_tim) }} vs {{ addslashes($match->awayTeam->nama_tim) }} ({{ addslashes($match->lapangan) }})')"
                                            class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 font-heading text-xs font-bold uppercase text-red-600 transition hover:bg-red-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                            title="Hapus jadwal laga"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada jadwal pertandingan yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Pop-up Modal Konfirmasi Hapus Jadwal Pertandingan -->
        <div
            x-show="showDeleteModal"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-garuda-obsidian/60 backdrop-blur-sm"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-match-title"
        >
            <div
                x-show="showDeleteModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                @click.outside="closeDeleteModal()"
                class="relative w-full max-w-md overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-2xl"
            >
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-red-100 text-red-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 id="modal-match-title" class="font-heading text-lg font-bold uppercase tracking-wide text-garuda-obsidian">
                            Hapus Jadwal Pertandingan
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            Apakah Anda yakin ingin menghapus jadwal <span class="font-bold text-garuda-obsidian" x-text="matchTitle"></span>? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button
                        type="button"
                        @click="closeDeleteModal()"
                        class="w-full sm:w-auto rounded-xl border border-gray-300 bg-white px-5 py-2.5 font-heading text-xs font-bold uppercase tracking-wider text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    >
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="w-full sm:w-auto rounded-xl bg-red-600 px-5 py-2.5 font-heading text-xs font-bold uppercase tracking-wider text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                        >
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        document.addEventListener('click', async (event) => {
            if (!(event.target instanceof Element)) {
                return;
            }

            const button = event.target.closest('[data-score-action]');
            if (!button) {
                return;
            }

            const row = button.closest('[data-match-row]');
            const feedback = row?.querySelector('[data-score-feedback]');
            if (!row || !feedback) {
                return;
            }

            const controls = row.querySelectorAll('[data-score-action]');
            controls.forEach((control) => {
                control.disabled = true;
            });
            feedback.textContent = 'Menyimpan skor...';

            try {
                const response = await fetch(button.dataset.scoreUrl, {
                    method: 'PATCH',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        side: button.dataset.scoreSide,
                        action: button.dataset.scoreAction,
                    }),
                });
                const result = await response.json();
                if (!response.ok || !result.success || !result.data) {
                    throw new Error(result.message || 'Skor gagal diperbarui.');
                }

                row.querySelector('[data-score-field="home"]').value = result.data.skor_home;
                row.querySelector('[data-score-field="away"]').value = result.data.skor_away;
                feedback.textContent = 'Skor berhasil diperbarui.';
            } catch (error) {
                feedback.textContent = error instanceof Error ? error.message : 'Skor gagal diperbarui.';
                console.error('Gagal memperbarui skor pertandingan:', error);
            } finally {
                controls.forEach((control) => {
                    control.disabled = false;
                });
            }
        });
    </script>
@endpush
