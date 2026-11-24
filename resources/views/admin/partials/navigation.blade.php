@php
    $adminNavigation = [
        ['route' => 'admin.dashboard', 'label' => 'Overview'],
        ['route' => 'admin.teams.index', 'label' => 'Verifikasi Tim'],
        ['route' => 'admin.matches.index', 'label' => 'Jadwal & Live Score'],
    ];
@endphp

<nav class="flex flex-wrap gap-2" aria-label="Navigasi dashboard panitia">
    @foreach ($adminNavigation as $item)
        @php($active = request()->routeIs($item['route']))
        <a
            href="{{ route($item['route']) }}"
            @if ($active) aria-current="page" @endif
            class="rounded-lg px-4 py-2 font-heading text-xs font-semibold uppercase transition {{ $active ? 'bg-garuda-pitch text-white' : 'border border-gray-200 bg-white text-garuda-obsidian hover:border-garuda-pitch' }}"
        >
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>
