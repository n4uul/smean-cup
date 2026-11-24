<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMEAN Cup 2026 — SMKN 1 Garut')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex min-h-screen flex-col bg-[#F8F9FA] font-sans text-garuda-obsidian antialiased selection:bg-garuda-mint selection:text-garuda-obsidian">
    <nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-gray-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between">
                <a href="{{ route('home') }}" aria-label="SMEAN Cup 2026 — Beranda" class="inline-flex items-center">
                    <img src="{{ asset('images/banner-smean-cup.png') }}" alt="SMEAN Cup 2026" class="h-auto w-44 object-contain sm:w-52">
                </a>

                <div class="hidden items-center gap-8 font-heading text-xs font-semibold uppercase tracking-wide md:flex">
                    <a href="{{ route('home') }}" class="transition hover:text-garuda-pitch">Beranda</a>
                    <a href="{{ route('public.schedule') }}" class="transition hover:text-garuda-pitch">Jadwal &amp; Live Score</a>

                    @guest
                        <div class="flex items-center gap-3 border-l border-gray-200 pl-5">
                            <a href="{{ route('login') }}" class="px-3 py-2 transition hover:text-garuda-pitch">Masuk</a>
                            <a href="{{ route('register') }}" class="rounded-lg bg-garuda-mint px-5 py-2.5 text-garuda-obsidian transition hover:bg-emerald-400">
                                Daftar Tim
                            </a>
                        </div>
                    @else
                        <div class="flex items-center gap-4 border-l border-gray-200 pl-5">
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-garuda-pitch">Overview Panitia</a>
                                <a href="{{ route('admin.teams.index') }}" class="hover:text-garuda-pitch">Verifikasi Tim</a>
                                <a href="{{ route('admin.matches.index') }}" class="hover:text-garuda-pitch">Jadwal &amp; Live Score</a>
                            @else
                                <a href="{{ route('dashboard') }}" class="hover:text-garuda-pitch">Dashboard Tim</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-md border border-gray-300 px-3 py-1.5 text-xs text-gray-600 transition hover:border-garuda-pitch hover:text-garuda-pitch">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>

                <button
                    type="button"
                    @click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-label="Buka menu navigasi"
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 p-2 text-garuda-obsidian transition hover:bg-gray-50 md:hidden"
                >
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="open" x-cloak x-transition class="border-t border-gray-200 bg-white px-4 pb-4 pt-3 md:hidden">
            <div class="flex flex-col gap-2 font-heading text-xs font-semibold uppercase tracking-wide">
                <a href="{{ route('home') }}" class="rounded-md px-3 py-2 hover:bg-gray-50">Beranda</a>
                <a href="{{ route('public.schedule') }}" class="rounded-md px-3 py-2 hover:bg-gray-50">Jadwal &amp; Live Score</a>
                @guest
                    <a href="{{ route('login') }}" class="rounded-md px-3 py-2 hover:bg-gray-50">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-garuda-mint px-3 py-2 text-center text-garuda-obsidian">Daftar Tim</a>
                @else
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="rounded-md px-3 py-2 hover:bg-gray-50">Overview Panitia</a>
                        <a href="{{ route('admin.teams.index') }}" class="rounded-md px-3 py-2 hover:bg-gray-50">Verifikasi Tim</a>
                        <a href="{{ route('admin.matches.index') }}" class="rounded-md px-3 py-2 hover:bg-gray-50">Jadwal &amp; Live Score</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="rounded-md px-3 py-2 hover:bg-gray-50">Dashboard Tim</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-md px-3 py-2 text-left text-gray-600 hover:bg-gray-50">Keluar</button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    @isset($header)
        <header class="border-b border-gray-200 bg-white px-4 py-6 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl font-heading text-garuda-obsidian">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="flex-grow">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>

    <footer class="mt-12 border-t border-gray-200 bg-white py-7">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 text-center sm:px-6 md:flex-row md:text-left lg:px-8">
            <a href="{{ route('home') }}" aria-label="SMEAN Cup — Beranda">
                <img src="{{ asset('images/banner-smean-cup.png') }}" alt="SMEAN Cup 2026" class="h-auto w-36 object-contain">
            </a>
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} <strong class="text-garuda-obsidian">SMEAN Cup — SMKN 1 Garut</strong>. All rights reserved.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
