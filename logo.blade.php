@props(['dark' => false, 'compact' => false])
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-aqua to-ocean text-white shadow-lg shadow-aqua/30">
        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 14c2.5-2.5 5-2.5 7.5 0s5 2.5 7.5 0 3-1.5 5-.5M2 9c2.5-2.5 5-2.5 7.5 0s5 2.5 7.5 0 3-1.5 5-.5"/></svg>
    </span>
    @unless($compact)
        <span class="text-lg font-extrabold tracking-tight {{ $dark ? 'text-navy' : 'text-white' }}">AQUA<span class="text-aqua">FLOW</span></span>
    @endunless
</span>
