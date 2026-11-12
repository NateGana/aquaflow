@props(['level'])
@php
    $classes = [
        'beginner' => 'bg-emerald-100 text-emerald-700',
        'intermediate' => 'bg-amber-100 text-amber-700',
        'advanced' => 'bg-rose-100 text-rose-700',
    ];
@endphp
<span {{ $attributes->merge(['class' => 'badge '.($classes[$level] ?? 'bg-slate-100 text-slate-600')]) }}>{{ \App\Support\Level::label($level) }}</span>
