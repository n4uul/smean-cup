<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-3xl uppercase tracking-wide text-garuda-obsidian">
            Pengaturan Akun
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-2xl border border-[#315635] bg-garuda-pitch p-4 shadow-sm sm:rounded-2xl sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-2xl border border-[#315635] bg-garuda-pitch p-4 shadow-sm sm:rounded-2xl sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
