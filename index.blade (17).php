@extends('layouts.dashboard')
@section('title', 'My Achievements')

@section('content')
<x-page-header title="My achievements" :subtitle="$earned->count().' of '.$achievements->count().' badges earned. Badges are awarded automatically when you meet their requirements.'"/>

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
    @foreach($achievements as $badge)
        @php $own = $earned->get($badge->id); @endphp
        <article class="card relative overflow-hidden p-6 {{ $own ? '' : 'opacity-75' }}">
            <div class="flex items-center gap-4">
                <span class="grid h-14 w-14 place-items-center rounded-2xl {{ $own ? 'bg-gradient-to-br from-aqua to-ocean text-white shadow-lg shadow-aqua/30' : 'bg-slate-100 text-slate-400' }}"><x-icon :name="$badge->icon" class="h-7 w-7"/></span>
                <div>
                    <h2 class="text-lg">{{ $badge->name }}</h2>
                    <p class="text-xs font-semibold {{ $own ? 'text-emerald-600' : 'text-slate-400' }}">{{ $own ? 'Earned '.\Illuminate\Support\Carbon::parse($own->pivot->awarded_at)->format('d M Y') : 'Locked' }}</p>
                </div>
            </div>
            <p class="mt-4 text-sm text-slate-500">{{ $badge->description }}</p>
            <p class="mt-3 text-xs font-semibold text-amber-600">+{{ $badge->points }} points</p>
        </article>
    @endforeach
</div>
@endsection
