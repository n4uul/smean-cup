<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-lg border border-red-300 bg-white px-4 py-2 font-heading text-xs font-semibold uppercase tracking-wide text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500']) }}>
    {{ $slot }}
</button>
