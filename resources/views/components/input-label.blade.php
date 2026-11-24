@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-garuda-white']) }}>
    {{ $value ?? $slot }}
</label>
