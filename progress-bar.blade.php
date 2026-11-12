@props(['percent' => 0, 'label' => null, 'detail' => null])
<div {{ $attributes }}>
    @if($label)
        <div class="mb-1.5 flex items-center justify-between text-sm">
            <span class="font-semibold text-navy">{{ $label }}</span>
            <span class="text-slate-500">{{ $detail ?? $percent.'%' }}</span>
        </div>
    @endif
    <div class="h-2.5 overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100">
        <div class="h-full rounded-full bg-gradient-to-r from-ocean to-aqua transition-all duration-500" style="width: {{ max(0, min(100, $percent)) }}%"></div>
    </div>
</div>
