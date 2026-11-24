<x-guest-layout>
    <div class="mb-7">
        <p class="font-heading text-xs font-semibold uppercase tracking-widest text-garuda-mint">Area Peserta</p>
        <h2 class="mt-2 font-display text-4xl uppercase leading-none text-garuda-white">Masuk ke Akun</h2>
        <p class="mt-2 text-sm text-gray-200">Akses dashboard tim dan informasi pendaftaran SMEAN Cup 2026.</p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-gray-100">
                <input id="remember_me" type="checkbox" class="rounded border-[#547158] bg-garuda-obsidian text-garuda-mint focus:ring-garuda-mint" name="remember">
                <span>{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-garuda-mint underline-offset-4 hover:underline focus:outline-none focus:ring-2 focus:ring-garuda-mint" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                Masuk
            </x-primary-button>
        </div>
    </form>

    <p class="mt-6 border-t border-[#547158] pt-5 text-center text-sm text-gray-200">
        Belum mendaftarkan akun?
        <a href="{{ route('register') }}" class="font-semibold text-garuda-gold underline-offset-4 hover:underline">Daftar tim</a>
    </p>
</x-guest-layout>
