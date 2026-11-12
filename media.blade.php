@props(['src' => null, 'seed' => 0, 'label' => null, 'icon' => 'droplet'])
@php
    $palettes = [
        ['#087ca7', '#16c5d8', '#071b33'],
        ['#0b2945', '#087ca7', '#16c5d8'],
        ['#16c5d8', '#087ca7', '#0b2945'],
        ['#071b33', '#087ca7', '#ddf8fa'],
    ];
    $p = $palettes[((int) $seed) % count($palettes)];
    $uid = 'g'.md5($seed.$label);
@endphp
<div {{ $attributes->merge(['class' => 'relative overflow-hidden bg-deep']) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $label }}" class="h-full w-full object-cover" loading="lazy">
    @else
        <svg viewBox="0 0 400 240" preserveAspectRatio="xMidYMid slice" class="absolute inset-0 h-full w-full" role="img" aria-label="{{ $label }}">
            <defs>
                <linearGradient id="{{ $uid }}" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0" stop-color="{{ $p[0] }}"/>
                    <stop offset="1" stop-color="{{ $p[2] }}"/>
                </linearGradient>
            </defs>
            <rect width="400" height="240" fill="url(#{{ $uid }})"/>
            <g stroke="#fff" stroke-opacity=".12" stroke-width="2" fill="none">
                <path d="M0 150c50-25 100-25 150 0s100 25 150 0 70-18 100-8"/>
                <path d="M0 185c50-25 100-25 150 0s100 25 150 0 70-18 100-8"/>
                <path d="M0 220c50-25 100-25 150 0s100 25 150 0 70-18 100-8"/>
            </g>
            <circle cx="320" cy="55" r="46" fill="{{ $p[1] }}" fill-opacity=".25"/>
            <circle cx="345" cy="38" r="12" fill="#fff" fill-opacity=".12"/>
        </svg>
        <span class="absolute inset-0 grid place-items-center text-white/90"><x-icon :name="$icon" class="h-12 w-12 opacity-80"/></span>
    @endif
</div>
