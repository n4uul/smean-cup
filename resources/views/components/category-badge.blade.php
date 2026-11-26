@props(['kategori' => 'putra'])

@php
    $isPutri = ($kategori === 'putri');
    $label = $isPutri ? 'Putri' : 'Putra';
    $classes = $isPutri
        ? 'border-rose-200 bg-rose-50 text-rose-700'
        : 'border-sky-200 bg-sky-50 text-sky-700';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border px-2.5 py-0.5 font-heading text-[10px] font-bold uppercase tracking-wider {$classes}"]) }}>
    {{ $label }}
</span>
