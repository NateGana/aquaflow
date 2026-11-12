@extends('layouts.dashboard')
@section('title', $program->title)

@section('content')
<nav class="mb-4 text-sm text-slate-500" aria-label="Breadcrumb">
    <a href="{{ route('programs.index') }}" class="hover:text-ocean">Training programs</a> <span class="mx-1">/</span> <span class="text-navy">{{ $program->title }}</span>
</nav>

<div class="overflow-hidden rounded-3xl bg-gradient-to-br from-navy to-ocean p-8 text-white sm:p-10">
    <x-level-badge :level="$program->level" class="!bg-white/15 !text-white"/>
    <h1 class="mt-4 text-4xl font-extrabold text-white">{{ $program->title }}</h1>
    <p class="mt-3 max-w-2xl text-white/80">{{ $program->summary }}</p>
    <dl class="mt-6 flex flex-wrap gap-x-10 gap-y-3 text-white">
        <div><dt class="text-xs text-white/60">Duration</dt><dd class="font-bold">{{ $program->duration_weeks }} weeks</dd></div>
        <div><dt class="text-xs text-white/60">Sessions per week</dt><dd class="font-bold">{{ $program->sessions_per_week }}</dd></div>
        <div><dt class="text-xs text-white/60">Exercises</dt><dd class="font-bold">{{ $program->exercises->count() }}</dd></div>
    </dl>
</div>

<div class="mt-8 grid gap-8 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        @if($enrollment)
            <div class="card p-6">
                <x-progress-bar label="Completion progress" :percent="$percent" :detail="count($done).' of '.$program->exercises->count().' exercises'"/>
                @if($enrollment->completed_at)<p class="mt-3 text-sm font-semibold text-emerald-600">Program completed {{ $enrollment->completed_at->diffForHumans() }}. Well done!</p>@endif
            </div>
        @endif

        <h2 class="text-xl">Weekly training schedule</h2>
        @forelse($weeks as $week => $exercises)
            <section class="card overflow-hidden" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                <button type="button" class="flex w-full items-center justify-between px-6 py-4 text-left" @click="open = !open" :aria-expanded="open">
                    <span class="font-bold text-navy">Week {{ $week }}</span>
                    <span class="flex items-center gap-3 text-sm text-slate-500">{{ $exercises->whereIn('id', $done)->count() }}/{{ $exercises->count() }} done <x-icon name="chevron-down" class="h-4 w-4 transition" ::class="open && 'rotate-180'"/></span>
                </button>
                <ul x-show="open" class="divide-y divide-slate-100 border-t border-slate-100">
                    @foreach($exercises as $exercise)
                        @php $isDone = in_array($exercise->id, $done); @endphp
                        <li class="flex items-start gap-4 px-6 py-4">
                            @if($enrollment)
                                <form method="POST" action="{{ route('programs.toggle', [$program, $exercise]) }}">@csrf
                                    <button class="mt-0.5 grid h-6 w-6 place-items-center rounded-full border-2 transition {{ $isDone ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 hover:border-ocean' }}" aria-label="{{ $isDone ? 'Mark as not done' : 'Mark as done' }}" aria-pressed="{{ $isDone ? 'true' : 'false' }}">@if($isDone)<x-icon name="check" class="h-3.5 w-3.5"/>@endif</button>
                                </form>
                            @else
                                <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-mist text-xs font-bold text-ocean">{{ $exercise->day }}</span>
                            @endif
                            <div class="flex-1">
                                <p class="font-semibold text-navy {{ $isDone ? 'line-through opacity-60' : '' }}">{{ $exercise->title }}</p>
                                @if($exercise->description)<p class="mt-0.5 text-sm text-slate-500">{{ $exercise->description }}</p>@endif
                                <p class="mt-1 text-xs text-slate-400">Day {{ $exercise->day }}@if($exercise->distance_m) &middot; {{ number_format($exercise->distance_m) }} m @endif</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @empty
            <x-empty-state title="No exercises yet" text="This program's schedule is being prepared." icon="programs"/>
        @endforelse
    </div>

    <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
        <div class="card p-6">
            @auth
                @if($enrollment)
                    <p class="flex items-center gap-2 text-sm font-semibold text-emerald-600"><x-icon name="check" class="h-5 w-5"/> You are enrolled</p>
                    <p class="mt-2 text-sm text-slate-500">Tick off each exercise as you finish it.</p>
                @else
                    <h2 class="text-lg">Ready to start?</h2>
                    <p class="mt-2 text-sm text-slate-500">Enroll to track your completion across the whole program.</p>
                    <form method="POST" action="{{ route('programs.enroll', $program) }}" class="mt-4">@csrf<button class="btn btn-primary w-full">Enroll in this program</button></form>
                @endif
            @else
                <h2 class="text-lg">Ready to start?</h2>
                <p class="mt-2 text-sm text-slate-500">Log in to enroll and track your progress.</p>
                <a href="{{ route('login') }}" class="btn btn-primary mt-4 w-full">Log in to enroll</a>
            @endauth
        </div>
        <div class="card p-6">
            <h2 class="text-lg">Training objectives</h2>
            <ul class="list-check mt-4 space-y-2 text-sm">@foreach($program->objectives ?? [] as $item)<li>{{ $item }}</li>@endforeach</ul>
        </div>
        <div class="card p-6">
            <h2 class="text-lg">Required equipment</h2>
            <ul class="mt-4 flex flex-wrap gap-2">@foreach($program->equipment ?? [] as $item)<li class="badge bg-slate-100 text-slate-700">{{ $item }}</li>@endforeach</ul>
            <a href="{{ route('equipment.index') }}" class="mt-4 inline-block text-sm font-semibold text-ocean hover:underline">Read the equipment guide</a>
        </div>
    </aside>
</div>
@endsection
