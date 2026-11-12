@extends('layouts.dashboard')
@section('title', 'Training Programs')

@section('content')
<x-page-header title="Training programs" subtitle="Structured weekly plans. Enroll, tick off each exercise and watch your completion grow."/>

<x-filter-bar :action="route('programs.index')" :filters="$filters" :duration="'weeks'" placeholder="Search programs..."/>

@if($programs->isEmpty())
    <x-empty-state title="No programs found" text="Try another search or reset the filters." icon="search"/>
@else
    <div class="grid gap-6 lg:grid-cols-3">
        @foreach($programs as $program)
            @php
                $enrollment = $enrolled->get($program->id);
                $percent = $enrollment && $program->exercises_count ? (int) round(count($enrollment->completedIds()) / $program->exercises_count * 100) : 0;
            @endphp
            <article class="card card-hover flex flex-col overflow-hidden">
                <x-media :src="$program->imageUrl()" :seed="$program->id + 2" :label="$program->title" class="h-40" icon="programs"/>
                <div class="flex flex-1 flex-col p-6">
                    <div class="flex items-center gap-2">
                        <x-level-badge :level="$program->level"/>
                        <span class="text-xs text-slate-500">{{ $program->duration_weeks }} weeks &middot; {{ $program->sessions_per_week }} sessions per week</span>
                    </div>
                    <h2 class="mt-3 text-xl">{{ $program->title }}</h2>
                    <p class="mt-2 flex-1 text-sm text-slate-500">{{ $program->summary }}</p>
                    @if($enrollment)
                        <x-progress-bar class="mt-5" label="Your progress" :percent="$percent"/>
                    @endif
                    <a href="{{ route('programs.show', $program) }}" class="btn {{ $enrollment ? 'btn-outline' : 'btn-primary' }} mt-5">{{ $enrollment ? 'Continue program' : 'View program' }}</a>
                </div>
            </article>
        @endforeach
    </div>
@endif
@endsection
