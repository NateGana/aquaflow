@props(['title' => 'Nothing here yet', 'text' => null, 'icon' => 'droplet'])
<div {{ $attributes->merge(['class' => 'card flex flex-col items-center px-6 py-12 text-center']) }}>
    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-mist text-ocean"><x-icon :name="$icon" class="h-7 w-7"/></span>
    <h3 class="mt-4 text-lg">{{ $title }}</h3>
    @if($text)<p class="mt-1 max-w-md text-sm text-slate-500">{{ $text }}</p>@endif
    {{ $slot }}
</div>
