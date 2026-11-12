@props(['label', 'value', 'icon' => 'droplet', 'hint' => null])
<div {{ $attributes->merge(['class' => 'card p-5']) }}>
    <div class="flex items-center justify-between">
        <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
        <span class="grid h-9 w-9 place-items-center rounded-xl bg-mist text-ocean"><x-icon :name="$icon" class="h-5 w-5"/></span>
    </div>
    <p class="mt-3 text-2xl font-extrabold text-navy">{{ $value }}</p>
    @if($hint)<p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>@endif
</div>
