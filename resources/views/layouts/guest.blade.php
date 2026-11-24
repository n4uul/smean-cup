<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Akun — SMEAN Cup 2026')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-[#F8F9FA] font-sans text-garuda-obsidian antialiased">
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" aria-label="SMEAN Cup — Beranda">
                <img src="{{ asset('images/banner-smean-cup.png') }}" alt="SMEAN Cup 2026" class="h-auto w-44 object-contain sm:w-52">
            </a>
            <a href="{{ route('home') }}" class="font-heading text-xs font-semibold uppercase tracking-wide text-gray-600 transition hover:text-garuda-pitch">
                Kembali ke Beranda
            </a>
        </div>
    </header>

    <main class="flex flex-1 items-center justify-center px-4 py-12">
        <div class="w-full max-w-lg overflow-hidden rounded-3xl border border-[#315635] bg-garuda-pitch p-6 text-garuda-white shadow-sm sm:p-9">
            <div class="mb-8">
                <p class="font-heading text-xs font-semibold uppercase tracking-[0.18em] text-garuda-mint">SMKN 1 Garut</p>
                <h1 class="mt-2 font-display text-4xl uppercase leading-none text-garuda-white">SMEAN Cup 2026</h1>
            </div>

            {{ $slot }}
        </div>
    </main>

    <footer class="py-5 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} SMEAN Cup — SMKN 1 Garut
    </footer>
</body>
</html>
