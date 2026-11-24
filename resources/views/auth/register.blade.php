<x-guest-layout>
    <div class="mb-7">
        <p class="font-heading text-xs font-semibold uppercase tracking-widest text-garuda-mint">Pendaftaran Peserta</p>
        <h2 class="mt-2 font-display text-4xl uppercase leading-none text-garuda-white">Buat Akun Tim</h2>
        <p class="mt-2 text-sm text-gray-200">Daftarkan akun perwakilan tim untuk melanjutkan pendaftaran SMEAN Cup 2026.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Penanggung Jawab" />
            <x-text-input id="name" class="mt-1" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" class="mt-1" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                Daftar Tim
            </x-primary-button>
        </div>
    </form>

    <p class="mt-6 border-t border-[#547158] pt-5 text-center text-sm text-gray-200">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="font-semibold text-garuda-gold underline-offset-4 hover:underline">Masuk di sini</a>
    </p>
</x-guest-layout>
