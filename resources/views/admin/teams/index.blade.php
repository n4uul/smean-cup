<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8 px-4 py-10 sm:px-6 lg:px-8">
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
                            <th scope="col" class="px-4 py-3">Pendaftar</th>
                            <th scope="col" class="px-4 py-3">Pelatih / Kontak</th>
                            <th scope="col" class="px-4 py-3">Berkas</th>
                            <th scope="col" class="px-4 py-3">Status</th>
                            <th scope="col" class="px-4 py-3 text-center">Keputusan</th>
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
                                    <div class="flex flex-wrap justify-center gap-2">
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500">Belum ada data tim yang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
