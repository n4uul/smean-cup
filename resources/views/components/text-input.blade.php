@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full rounded-lg border border-[#547158] bg-garuda-obsidian px-3 py-2 text-sm text-garuda-white shadow-sm placeholder:text-gray-400 focus:border-garuda-mint focus:outline-none focus:ring-2 focus:ring-garuda-mint/30 disabled:cursor-not-allowed disabled:opacity-70']) }}>
