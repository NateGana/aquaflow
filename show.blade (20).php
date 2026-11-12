@extends('layouts.dashboard')
@section('title', $technique->title)

@section('content')
<nav class="mb-4 text-sm text-slate-500" aria-label="Breadcrumb">
    <a href="{{ route('techniques.index') }}" class="hover:text-ocean">Swimming techniques</a> <span class="mx-1">/</span> <span class="text-navy">{{ $technique->title }}</span>
</nav>

<div class="overflow-hidden rounded-3xl bg-navy text-white">
    <div class="grid lg:grid-cols-2">
        <div class="p-8 sm:p-10">
            <x-level-badge :level="$technique->level" class="!bg-white/15 !text-white"/>
            <h1 class="mt-4 text-4xl font-extrabold text-white">{{ $technique->title }}</h1>
            <p class="mt-4 leading-relaxed text-white/80">{{ $technique->description }}</p>
        </div>
        <x-media :src="$technique->imageUrl()" :seed="$technique->id + 1" :label="$technique->title" class="min-h-56" icon="techniques"/>
    </div>
</div>

<div class="mt-8 grid gap-8 lg:grid-cols-3">
    <div class="space-y-8 lg:col-span-2">
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach([['Proper body position', $technique->body_position, 'user'], ['Arm movement', $technique->arm_movement, 'techniques'], ['Leg movement', $technique->leg_movement, 'bolt'], ['Breathing pattern', $technique->breathing_pattern, 'droplet']] as [$title, $text, $icon])
                <section class="card p-6">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-mist text-ocean"><x-icon :name="$icon" class="h-5 w-5"/></span>
                    <h2 class="mt-3 text-lg">{{ $title }}</h2>
                    <p class="mt-2 text-sm leading-relaxed">{{ $text }}</p>
                </section>
            @endforeach
        </div>

        <section class="card p-6">
            <h2 class="text-xl">Step-by-step instructions</h2>
            <ol class="mt-5 space-y-4">
                @foreach($technique->steps ?? [] as $index => $step)
                    <li class="flex gap-4"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-ocean text-sm font-bold text-white">{{ $index + 1 }}</span><p class="pt-1 leading-relaxed">{{ $step }}</p></li>
                @endforeach
            </ol>
        </section>

        <div class="grid gap-6 sm:grid-cols-2">
            <section class="card border-l-4 !border-l-amber-400 p-6">
                <h2 class="text-lg">Common mistakes</h2>
                <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-relaxed">@foreach($technique->mistakes ?? [] as $item)<li>{{ $item }}</li>@endforeach</ul>
            </section>
            <section class="card border-l-4 !border-l-aqua p-6">
                <h2 class="text-lg">Improvement tips</h2>
                <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-relaxed">@foreach($technique->tips ?? [] as $item)<li>{{ $item }}</li>@endforeach</ul>
            </section>
        </div>
    </div>

    <aside class="space-y-6">
        <div class="card p-6">
            <h2 class="text-lg">Related lessons</h2>
            <ul class="mt-4 space-y-3">
                @forelse($lessons as $lesson)
                    <li><a href="{{ route('lessons.show', $lesson) }}" class="flex items-center justify-between gap-3 rounded-lg p-2 hover:bg-slate-50"><span class="text-sm font-semibold text-navy">{{ $lesson->title }}</span><x-level-badge :level="$lesson->level"/></a></li>
                @empty
                    <li class="text-sm text-slate-500">No related lessons yet. <a href="{{ route('lessons.index') }}" class="font-semibold text-ocean hover:underline">Browse the academy</a>.</li>
                @endforelse
            </ul>
        </div>
        <div class="card p-6">
            <h2 class="text-lg">More techniques</h2>
            <ul class="mt-4 space-y-2">
                @foreach($others as $other)
                    <li><a href="{{ route('techniques.show', $other) }}" class="flex items-center justify-between rounded-lg p-2 text-sm font-semibold text-navy hover:bg-slate-50">{{ $other->title }} <x-icon name="chevron-right" class="h-4 w-4 text-slate-400"/></a></li>
                @endforeach
            </ul>
        </div>
    </aside>
</div>
@endsection
