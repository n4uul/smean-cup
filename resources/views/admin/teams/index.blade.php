<x-app-layout>
    <div
        x-data="{
            showDeleteModal: false,
            deleteUrl: '',
            teamName: '',
            openDeleteModal(url, name) {
                this.deleteUrl = url;
                this.teamName = name;
                this.showDeleteModal = true;
            },
            closeDeleteModal() {
                this.showDeleteModal = false;
                this.deleteUrl = '';
                this.teamName = '';
            }
        }"
        @keydown.escape.window="closeDeleteModal()"
        class="mx-auto max-w-7xl space-y-8 px-4 py-10 sm:px-6 lg:px-8"
    >
        <header class="flex flex-col justify-between gap-6 border-b border-gray-200 pb-6 lg:flex-row lg:items-end">
            <div>
                <p class="font-heading text-xs font-bold uppercase tracking-widest text-garuda-pitch">SMEAN Cup 2026</p>
                <h1 class="mt-2 font-display text-4xl uppercase tracking-wide text-garuda-obsidian sm:text-5xl">Verifikasi Pendaftaran Tim</h1>
                <p class="mt-2 text-sm text-gray-600">Periksa data pendaftar dan berkas persyaratan sebelum memberi keputusan.</p>
            </div>
            @include('admin.partials.navigation')
        </header>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">
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

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col justify-between gap-2 border-b border-gray-200 p-6 sm:flex-row sm:items-center">
                <div>
                    <h2 class="font-heading text-lg font-bold text-garuda-obsidian">Tim Pendaftar</h2>
                    <p class="mt-1 text-sm text-gray-500">Logo dan berkas dibuka pada tab baru untuk ditinjau.</p>
                </div>
                <span class="text-sm font-semibold text-gray-600">{{ $teams->count() }} pendaftar</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left font-sans text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50 font-heading text-xs uppercase text-gray-600">
                            <th scope="col" class="px-4 py-3">Tim &amp; Sekolah</th>
                            <th scope="col" class="px-4 py-3">Kategori</th>
                            <th scope="col" class="px-4 py-3">Pendaftar</th>
                            <th scope="col" class="px-4 py-3">Pelatih / Kontak</th>
                            <th scope="col" class="px-4 py-3">Berkas</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3 text-center">Aksi / Keputusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($teams as $team)
                            @php
                                $statusClass = match ($team->status_verifikasi) {
                                    'approved' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
                                    'rejected' => 'border-red-200 bg-red-50 text-red-800',
                                    default => 'border-amber-200 bg-amber-50 text-amber-800',
                                };
                            @endphp
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($team->logo_tim)
                                            <a href="{{ asset('storage/'.$team->logo_tim) }}" target="_blank" rel="noopener noreferrer" aria-label="Buka logo {{ $team->nama_tim }}">
                                                <img src="{{ asset('storage/'.$team->logo_tim) }}" alt="" class="h-12 w-12 rounded-lg border border-gray-200 bg-white object-contain p-1">
                                            </a>
                                        @else
                                            <span class="flex h-12 w-12 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 font-heading text-[10px] font-bold text-gray-400">LOGO</span>
                                        @endif
                                        <div>
                                            <strong class="block font-heading text-sm text-garuda-obsidian">{{ $team->nama_tim }}</strong>
                                            <span class="text-xs text-gray-500">{{ $team->asal_sekolah }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <x-category-badge :kategori="$team->kategori ?? 'putra'" />
                                </td>
                                <td class="px-4 py-4">
                                    <span class="block font-medium text-garuda-obsidian">{{ $team->user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $team->user->email }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="block text-garuda-obsidian">{{ $team->nama_pelatih }}</span>
                                    <span class="text-xs text-gray-500">{{ $team->no_wa }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    @if ($team->berkas_syarat)
                                        <a href="{{ asset('storage/'.$team->berkas_syarat) }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-xs text-garuda-pitch hover:underline">
                                            Buka berkas
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-500">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full border px-3 py-1 font-heading text-xs font-bold uppercase {{ $statusClass }}">
                                        {{ $team->status_verifikasi === 'approved' ? 'Disetujui' : ($team->status_verifikasi === 'rejected' ? 'Ditolak' : 'Menunggu') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center justify-center gap-2">
                                        <form action="{{ route('admin.teams.updateStatus', $team) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_verifikasi" value="approved">
                                            <button type="submit" class="rounded-lg bg-garuda-mint px-3 py-2 font-heading text-xs font-bold uppercase text-garuda-obsidian transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-garuda-pitch focus:ring-offset-2">
                                                Setujui
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.teams.updateStatus', $team) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status_verifikasi" value="rejected">
                                            <button type="submit" class="rounded-lg border border-red-200 bg-white px-3 py-2 font-heading text-xs font-bold uppercase text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                Tolak
                                            </button>
                                        </form>
                                        <button
                                            type="button"
                                            @click="openDeleteModal('{{ route('admin.teams.destroy', $team) }}', '{{ addslashes($team->nama_tim) }}')"
                                            class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 font-heading text-xs font-bold uppercase text-red-600 transition hover:bg-red-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                            title="Hapus data tim"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-gray-500">Belum ada data tim yang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Pop-up Modal Konfirmasi Hapus Tim -->
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
            aria-labelledby="modal-team-title"
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
                        <h3 id="modal-team-title" class="font-heading text-lg font-bold uppercase tracking-wide text-garuda-obsidian">
                            Hapus Data Tim
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            Apakah Anda yakin ingin menghapus tim <span class="font-bold text-garuda-obsidian" x-text="teamName"></span>? Seluruh jadwal pertandingan, logo, dan berkas persyaratan terkait akan ikut dibersihkan. Tindakan ini tidak dapat dibatalkan.
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
