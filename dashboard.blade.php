@extends('layouts.dashboard')
@section('title', 'Dashboard')

@section('content')
@php
    $weekChart = [
        'type' => 'bar',
        'labels' => $weekly['labels'],
        'datasets' => [['label' => 'Distance (m)', 'data' => $weekly['values']]],
        'yTitle' => 'Meters',
    ];
@endphp

<x-page-header :title="'Welcome back, '.auth()->user()->name.'!'" subtitle="Here is where your swimming stands today.">
    <x-slot:actions>
        <a href="{{ route('records.index') }}" class="btn btn-primary"><x-icon name="plus" class="h-4 w-4"/> Log a swim</a>
    </x-slot:actions>
</x-page-header>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
    <x-stat-card label="Lessons completed" :value="$stats['lessons']" icon="academy"/>
    <x-stat-card label="Swimming sessions" :value="$stats['sessions']" icon="tracker"/>
    <x-stat-card label="Distance swum" :value="number_format($stats['distance']).' m'" icon="droplet"/>
    <x-stat-card label="Learning level" :value="$stats['level']" icon="compass"/>
    <x-stat-card label="Achievement points" :value="number_format($stats['points'])" icon="award"/>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    <section class="card overflow-hidden lg:col-span-1">
        <div class="bg-gradient-to-br from-deep to-ocean p-6 text-white">
            <p class="text-sm font-medium text-white/70">Continue learning</p>
            @if($continue)
                <h2 class="mt-2 text-xl text-white">{{ $continue->title }}</h2>
                <p class="mt-2 text-sm text-white/75">{{ $continue->summary }}</p>
                <div class="mt-4 flex items-center gap-3 text-xs text-white/80">
                    <x-level-badge :level="$continue->level" class="!bg-white/15 !text-white"/>
                    <span class="inline-flex items-center gap-1"><x-icon name="clock" class="h-3.5 w-3.5"/> {{ $continue->duration_minutes }} min</span>
                </div>
                <a href="{{ route('lessons.show', $continue) }}" class="btn btn-aqua mt-6">Continue Learning</a>
            @else
                <h2 class="mt-2 text-xl text-white">You have finished every lesson</h2>
                <p class="mt-2 text-sm text-white/75">Try a training program or a new challenge to keep going.</p>
                <a href="{{ route('programs.index') }}" class="btn btn-aqua mt-6">Browse programs</a>
            @endif
        </div>
    </section>

    <section class="card p-6 lg:col-span-2">
        <h2 class="text-lg">My weekly activity</h2>
        <p class="text-sm text-slate-500">Distance swum over the last seven days.</p>
        <div class="mt-4 h-56"><canvas x-data="chart(@js($weekChart))"></canvas></div>
    </section>

    <section class="card p-6 lg:col-span-1">
        <h2 class="text-lg">Learning progress</h2>
        <div class="mt-5 space-y-5">
            @foreach($skills as $skill)
                <x-progress-bar :label="$skill['label']" :percent="$skill['percent']" :detail="$skill['done'].'/'.$skill['total'].' lessons'"/>
            @endforeach
        </div>
    </section>

    <section class="card p-6 lg:col-span-1">
        <div class="flex items-center justify-between">
            <h2 class="text-lg">Upcoming training</h2>
            <a href="{{ route('schedule.index') }}" class="text-xs font-semibold text-ocean hover:underline">Open schedule</a>
        </div>
        <ul class="mt-4 divide-y divide-slate-100">
            @forelse($upcoming as $session)
                <li class="flex items-center gap-3 py-3">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-mist text-center text-ocean">
                        <span><span class="block text-[10px] font-semibold leading-none">{{ $session->scheduled_on->format('M') }}</span><span class="block text-base font-extrabold leading-none">{{ $session->scheduled_on->format('d') }}</span></span>
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-navy">{{ $session->activity }}</p>
                        <p class="text-xs text-slate-500">{{ $session->scheduled_on->format('D') }} at {{ $session->timeLabel() }}</p>
                    </div>
                </li>
            @empty
                <li class="py-6 text-center text-sm text-slate-500">No sessions planned. <a href="{{ route('schedule.index') }}" class="font-semibold text-ocean hover:underline">Schedule one</a>.</li>
            @endforelse
        </ul>
    </section>

    <section class="card p-6 lg:col-span-1">
        <div class="flex items-center justify-between">
            <h2 class="text-lg">Recent achievements</h2>
            <a href="{{ route('achievements.index') }}" class="text-xs font-semibold text-ocean hover:underline">View all</a>
        </div>
        <ul class="mt-4 space-y-4">
            @forelse($achievements as $badge)
                <li class="flex items-center gap-3">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-aqua to-ocean text-white"><x-icon :name="$badge->icon" class="h-5 w-5"/></span>
                    <div>
                        <p class="text-sm font-semibold text-navy">{{ $badge->name }}</p>
                        <p class="text-xs text-slate-500">{{ \Illuminate\Support\Carbon::parse($badge->pivot->awarded_at)->diffForHumans() }} &middot; +{{ $badge->points }} points</p>
                    </div>
                </li>
            @empty
                <li class="py-6 text-center text-sm text-slate-500">Complete a lesson to earn your first badge.</li>
            @endforelse
        </ul>
    </section>
</div>
@endsection
