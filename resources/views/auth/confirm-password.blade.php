<x-guest-layout>
    <div class="mb-7">
        <p class="font-heading text-xs font-semibold uppercase tracking-widest text-garuda-mint">Keamanan Akun</p>
        <h2 class="mt-2 font-display text-4xl uppercase leading-none text-garuda-white">Konfirmasi Kata Sandi</h2>
        <p class="mt-2 text-sm leading-6 text-gray-200">Masukkan kata sandi untuk melanjutkan ke area aman.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end">
            <x-primary-button>
                Konfirmasi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
