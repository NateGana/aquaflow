@extends('layouts.dashboard')
@section('title', 'Swim Academy')

@section('content')
<x-page-header title="Swim Academy" subtitle="A complete tutorial library organized by level. Work through the lessons in order or jump to what you need."/>

<x-filter-bar :action="route('lessons.index')" :filters="$filters" :categories="$categories" :duration="true" placeholder="Search lessons..."/>

@forelse($grouped as $level => $lessons)
    <section class="mb-12">
        <div class="mb-5 flex items-center gap-3">
            <h2 class="text-xl">{{ \App\Support\Level::label($level) }} level</h2>
            <span class="text-sm text-slate-500">{{ $lessons->count() }} {{ \Illuminate\Support\Str::plural('lesson', $lessons->count()) }}</span>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($lessons as $lesson)
                <article class="card card-hover flex flex-col overflow-hidden">
                    <div class="relative">
                        <x-media :src="$lesson->imageUrl()" :seed="$lesson->id" :label="$lesson->title" class="h-40"/>
                        @if(in_array($lesson->id, $completed))
                            <span class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-emerald-500 px-2.5 py-1 text-xs font-semibold text-white"><x-icon name="check" class="h-3.5 w-3.5"/> Completed</span>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-level-badge :level="$lesson->level"/>
                            <span class="badge bg-slate-100 text-slate-600">{{ $lesson->category->name }}</span>
                            <span class="inline-flex items-center gap-1 text-xs text-slate-500"><x-icon name="clock" class="h-3.5 w-3.5"/> {{ $lesson->duration_minutes }} min</span>
                        </div>
                        <h3 class="mt-3 text-lg">{{ $lesson->title }}</h3>
                        <p class="mt-1.5 flex-1 text-sm text-slate-500">{{ $lesson->summary }}</p>
                        <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-outline btn-sm mt-5 self-start">View Lesson</a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@empty
    <x-empty-state title="No lessons match your filters" text="Try a different search or reset the filters." icon="search">
        <a href="{{ route('lessons.index') }}" class="btn btn-primary mt-5">Reset filters</a>
    </x-empty-state>
@endforelse
@endsection
