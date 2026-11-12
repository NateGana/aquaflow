@extends('layouts.dashboard')
@section('title', 'Challenges')

@section('content')
<x-page-header title="Swimming challenges" subtitle="Join a challenge, log your swims and earn reward points automatically."/>

@if($items->isEmpty())
    <x-empty-state title="No challenges available" text="New challenges are added regularly. Check back soon." icon="challenges"/>
@else
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach($items as $item)
            @php
                $challenge = $item['challenge'];
                $participant = $item['participant'];
                $completed = $participant?->completed_at;
            @endphp
            <article class="card flex flex-col p-6">
                <div class="flex items-start justify-between gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-xl {{ $completed ? 'bg-emerald-100 text-emerald-600' : 'bg-mist text-ocean' }}"><x-icon :name="$completed ? 'check' : 'challenges'" class="h-5 w-5"/></span>
                    <span class="badge bg-amber-100 text-amber-700">+{{ number_format($challenge->points) }} points</span>
                </div>
                <h2 class="mt-4 text-lg">{{ $challenge->title }}</h2>
                <p class="mt-1.5 text-sm text-slate-500">{{ $challenge->description }}</p>
                <dl class="mt-4 grid grid-cols-2 gap-3 text-xs">
                    <div><dt class="text-slate-400">Target</dt><dd class="font-semibold text-navy">{{ number_format($challenge->target) }} {{ $challenge->unit() }}</dd></div>
                    <div><dt class="text-slate-400">Runs</dt><dd class="font-semibold text-navy">{{ $challenge->starts_on->format('d M') }} to {{ $challenge->ends_on->format('d M Y') }}</dd></div>
                </dl>
                <div class="mt-5 flex-1">
                    @if($participant)
                        <x-progress-bar label="Progress" :percent="$item['percent']" :detail="number_format($item['value']).' / '.number_format($challenge->target).' '.$challenge->unit()"/>
                    @endif
                </div>
                <div class="mt-5">
                    @if($completed)
                        <p class="flex items-center gap-2 text-sm font-semibold text-emerald-600"><x-icon name="check" class="h-4 w-4"/> Completed {{ $completed->diffForHumans() }}</p>
                    @elseif($participant)
                        <p class="text-sm font-semibold text-ocean">{{ $challenge->hasEnded() ? 'Challenge ended' : 'In progress' }}</p>
                    @elseif($challenge->isJoinable())
                        <form method="POST" action="{{ route('challenges.join', $challenge) }}">@csrf<button class="btn btn-primary w-full">Join challenge</button></form>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
@endif
@endsection
