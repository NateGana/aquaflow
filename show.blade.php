@extends('layouts.dashboard')
@section('title', $lesson->title)

@section('content')
@php
    $done = $progress?->completed_at;
    $embed = $lesson->embedUrl();
@endphp

<nav class="mb-4 text-sm text-slate-500" aria-label="Breadcrumb">
    <a href="{{ route('lessons.index') }}" class="hover:text-ocean">Swim Academy</a> <span class="mx-1">/</span> <span class="text-navy">{{ $lesson->title }}</span>
</nav>

<div class="grid gap-8 lg:grid-cols-3">
    <div class="space-y-8 lg:col-span-2">
        <header>
            <div class="flex flex-wrap items-center gap-2">
                <x-level-badge :level="$lesson->level"/>
                <span class="badge bg-slate-100 text-slate-600">{{ $lesson->category->name }}</span>
                <span class="inline-flex items-center gap-1 text-sm text-slate-500"><x-icon name="clock" class="h-4 w-4"/> {{ $lesson->duration_minutes }} minutes</span>
            </div>
            <h1 class="mt-3 text-3xl font-extrabold sm:text-4xl">{{ $lesson->title }}</h1>
            <p class="mt-3 text-lg text-slate-500">{{ $lesson->summary }}</p>
        </header>

        @if($embed)
            <div class="aspect-video overflow-hidden rounded-2xl bg-navy shadow-sm">
                <iframe src="{{ $embed }}" title="{{ $lesson->title }} video tutorial" class="h-full w-full" loading="lazy" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
            </div>
        @elseif($lesson->video_url)
            <a href="{{ $lesson->video_url }}" target="_blank" rel="noopener noreferrer" class="card flex items-center gap-4 p-5 hover:shadow-md">
                <span class="grid h-12 w-12 place-items-center rounded-xl bg-ocean text-white"><x-icon name="play" class="h-6 w-6"/></span>
                <span><span class="block font-semibold text-navy">Watch the video tutorial</span><span class="text-sm text-slate-500">Opens in a new tab</span></span>
            </a>
        @else
            <figure class="overflow-hidden rounded-2xl shadow-sm">
                <x-media :src="$lesson->imageUrl()" :seed="$lesson->id" :label="$lesson->title" class="h-64 sm:h-80"/>
                <figcaption class="bg-white px-5 py-3 text-sm text-slate-500">Image tutorial: follow the step-by-step instructions below.</figcaption>
            </figure>
        @endif

        <section class="card p-6">
            <h2 class="text-xl">Introduction</h2>
            <p class="mt-3 leading-relaxed">{{ $lesson->introduction }}</p>
        </section>

        <section class="card p-6">
            <h2 class="text-xl">Learning objectives</h2>
            <ul class="list-check mt-4 space-y-2.5">@foreach($lesson->objectives ?? [] as $item)<li>{{ $item }}</li>@endforeach</ul>
        </section>

        <section class="card p-6">
            <h2 class="text-xl">Step-by-step instructions</h2>
            <ol class="mt-5 space-y-4">
                @foreach($lesson->steps ?? [] as $index => $step)
                    <li class="flex gap-4">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-ocean text-sm font-bold text-white">{{ $index + 1 }}</span>
                        <p class="pt-1 leading-relaxed">{{ $step }}</p>
                    </li>
                @endforeach
            </ol>
        </section>

        <div class="grid gap-6 sm:grid-cols-2">
            <section class="card border-l-4 !border-l-amber-400 p-6">
                <h2 class="text-lg">Common mistakes</h2>
                <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-relaxed">@foreach($lesson->mistakes ?? [] as $item)<li>{{ $item }}</li>@endforeach</ul>
            </section>
            <section class="card border-l-4 !border-l-aqua p-6">
                <h2 class="text-lg">Tips for improvement</h2>
                <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-relaxed">@foreach($lesson->tips ?? [] as $item)<li>{{ $item }}</li>@endforeach</ul>
            </section>
        </div>

        <p class="rounded-xl bg-mist px-4 py-3 text-sm text-navy">Online lessons support, but do not replace, certified swimming instruction and lifeguard supervision. Practice in a supervised pool.</p>
    </div>

    <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
        <div class="card p-6">
            <h2 class="text-lg">Your progress</h2>
            @auth
                @if($done)
                    <p class="mt-3 flex items-center gap-2 text-sm font-semibold text-emerald-600"><x-icon name="check" class="h-5 w-5"/> Completed {{ $done->diffForHumans() }}</p>
                    <form method="POST" action="{{ route('lessons.reopen', $lesson) }}" class="mt-4">@csrf @method('DELETE')
                        <button class="btn btn-outline w-full">Mark as not completed</button>
                    </form>
                @else
                    <p class="mt-3 text-sm text-slate-500">Finished the lesson? Mark it complete to track progress and earn badges.</p>
                    <form method="POST" action="{{ route('lessons.complete', $lesson) }}" class="mt-4">@csrf
                        <button class="btn btn-primary w-full"><x-icon name="check" class="h-4 w-4"/> Mark as Completed</button>
                    </form>
                @endif
            @else
                <p class="mt-3 text-sm text-slate-500">Log in to track completed lessons and earn badges.</p>
                <a href="{{ route('login') }}" class="btn btn-primary mt-4 w-full">Log in to track progress</a>
            @endauth
        </div>

        <div class="card p-6">
            <h2 class="text-lg">Related lessons</h2>
            <ul class="mt-4 space-y-4">
                @foreach($related as $item)
                    <li>
                        <a href="{{ route('lessons.show', $item) }}" class="group flex items-center gap-3">
                            <x-media :src="$item->imageUrl()" :seed="$item->id" :label="$item->title" class="h-14 w-14 shrink-0 rounded-xl" icon="droplet"/>
                            <span><span class="block text-sm font-semibold text-navy group-hover:text-ocean">{{ $item->title }}</span><span class="text-xs text-slate-500">{{ \App\Support\Level::label($item->level) }} &middot; {{ $item->duration_minutes }} min</span></span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </aside>
</div>
@endsection
