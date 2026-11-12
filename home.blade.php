@extends('layouts.site')

@section('content')
@php
    $categoryIcons = ['beginner-swimming' => 'droplet', 'intermediate-swimming' => 'techniques', 'advanced-swimming' => 'bolt', 'swimming-fitness' => 'fire', 'water-safety' => 'shield'];
@endphp

<section class="relative isolate overflow-hidden bg-navy text-white">
    @if(file_exists(public_path('images/hero.jpg')))
        <img src="{{ asset('images/hero.jpg') }}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover opacity-50">
    @else
        <svg viewBox="0 0 1440 720" preserveAspectRatio="xMidYMid slice" class="absolute inset-0 -z-10 h-full w-full" aria-hidden="true">
            <defs>
                <linearGradient id="hero-water" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0" stop-color="#071B33"/><stop offset=".6" stop-color="#0B2945"/><stop offset="1" stop-color="#087CA7"/>
                </linearGradient>
                <radialGradient id="hero-glow" cx=".78" cy=".15" r=".65">
                    <stop offset="0" stop-color="#16C5D8" stop-opacity=".5"/><stop offset="1" stop-color="#16C5D8" stop-opacity="0"/>
                </radialGradient>
            </defs>
            <rect width="1440" height="720" fill="url(#hero-water)"/>
            <rect width="1440" height="720" fill="url(#hero-glow)"/>
            <g fill="#fff" fill-opacity=".05">
                <polygon points="900,0 1010,0 780,720 640,720"/>
                <polygon points="1100,0 1180,0 1040,720 930,720"/>
                <polygon points="1300,0 1360,0 1330,720 1210,720"/>
            </g>
            <g stroke="#DDF8FA" stroke-opacity=".22" stroke-width="3" fill="none">
                <path d="M0 520h1440"/><path d="M0 590h1440" stroke-opacity=".3"/><path d="M0 670h1440" stroke-opacity=".4"/>
            </g>
            <g fill="#16C5D8" fill-opacity=".55">
                @foreach(range(0, 28) as $i)
                    <circle cx="{{ 24 + $i * 52 }}" cy="520" r="5"/><circle cx="{{ 50 + $i * 52 }}" cy="590" r="6"/><circle cx="{{ 12 + $i * 52 }}" cy="670" r="7"/>
                @endforeach
            </g>
            <g stroke="#fff" stroke-opacity=".1" stroke-width="2" fill="none">
                <path d="M0 330c120-40 240-40 360 0s240 40 360 0 240-40 360 0 240 40 360 0"/>
                <path d="M0 390c120-40 240-40 360 0s240 40 360 0 240-40 360 0 240 40 360 0"/>
            </g>
        </svg>
    @endif
    <div class="mx-auto max-w-7xl px-4 pb-28 pt-40 sm:px-6 lg:px-8 lg:pb-40 lg:pt-48">
        <div class="max-w-2xl">
            <h1 class="text-5xl font-extrabold leading-[1.05] text-white sm:text-6xl">Make Every Stroke Count.</h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/80">Learn to swim, improve your technique, and build confidence in the water with AquaFlow.</p>
            <div class="mt-9 flex flex-wrap gap-3">
                <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="btn btn-aqua px-6 py-3">Start Learning</a>
                <a href="{{ route('lessons.index') }}" class="btn btn-light px-6 py-3">Explore Swimming Lessons</a>
            </div>
            <dl class="mt-12 flex flex-wrap gap-x-10 gap-y-4 text-white">
                <div><dt class="text-sm text-white/60">Lessons</dt><dd class="text-2xl font-bold">{{ $counts['lessons'] }}</dd></div>
                <div><dt class="text-sm text-white/60">Techniques</dt><dd class="text-2xl font-bold">{{ $counts['techniques'] }}</dd></div>
                <div><dt class="text-sm text-white/60">Training programs</dt><dd class="text-2xl font-bold">{{ $counts['programs'] }}</dd></div>
            </dl>
        </div>
    </div>
    <div class="wave-divider absolute inset-x-0 bottom-0 h-14"></div>
</section>

<section class="bg-soft py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="section-title">Swimming categories</h2>
        <p class="mt-2 max-w-2xl text-slate-500">Choose the path that matches where you are today.</p>
        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @foreach($categories as $category)
                <a href="{{ route('lessons.index', ['category' => $category->slug]) }}" class="card card-hover group p-5">
                    <span class="grid h-12 w-12 place-items-center rounded-xl bg-mist text-ocean transition group-hover:bg-ocean group-hover:text-white"><x-icon :name="$categoryIcons[$category->slug] ?? 'droplet'" class="h-6 w-6"/></span>
                    <h3 class="mt-4 text-base">{{ $category->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $category->lessons_count }} {{ \Illuminate\Support\Str::plural('lesson', $category->lessons_count) }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="section-title">Popular swimming lessons</h2>
                <p class="mt-2 text-slate-500">Step-by-step lessons with clear objectives and tips.</p>
            </div>
            <a href="{{ route('lessons.index') }}" class="hidden text-sm font-semibold text-ocean hover:underline sm:block">See all lessons</a>
        </div>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($lessons as $lesson)
                <article class="card card-hover flex flex-col overflow-hidden">
                    <x-media :src="$lesson->imageUrl()" :seed="$lesson->id" :label="$lesson->title" class="h-44" icon="droplet"/>
                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex items-center gap-2">
                            <x-level-badge :level="$lesson->level"/>
                            <span class="inline-flex items-center gap-1 text-xs text-slate-500"><x-icon name="clock" class="h-3.5 w-3.5"/> {{ $lesson->duration_minutes }} min</span>
                        </div>
                        <h3 class="mt-3 text-lg">{{ $lesson->title }}</h3>
                        <p class="mt-1.5 flex-1 text-sm text-slate-500">{{ $lesson->summary }}</p>
                        <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-primary btn-sm mt-5 self-start">View Lesson</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-navy py-16 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="section-title text-white">Why choose AquaFlow?</h2>
        <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['academy', 'Structured learning paths', 'Lessons are ordered from first float to advanced mechanics so you always know what comes next.'],
                ['users', 'Professional swimming guides', 'Clear instructions, common mistakes and improvement tips written for real swimmers.'],
                ['tracker', 'Personal progress monitoring', 'Log sessions, watch your distance grow and earn badges for the milestones you reach.'],
                ['shield', 'Safety-first education', 'A dedicated Water Safety Center keeps awareness and responsible habits at the core.'],
            ] as [$icon, $title, $text])
                <div>
                    <span class="grid h-12 w-12 place-items-center rounded-xl bg-aqua/15 text-aqua"><x-icon :name="$icon" class="h-6 w-6"/></span>
                    <h3 class="mt-4 text-lg text-white">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-soft py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="section-title">Featured swimming techniques</h2>
        <p class="mt-2 text-slate-500">Visual guides to the strokes and skills every swimmer should know.</p>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($techniques as $technique)
                <a href="{{ route('techniques.show', $technique) }}" class="card card-hover group overflow-hidden">
                    <x-media :src="$technique->imageUrl()" :seed="$technique->id + 1" :label="$technique->title" class="h-40" icon="techniques"/>
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg">{{ $technique->title }}</h3>
                            <x-level-badge :level="$technique->level"/>
                        </div>
                        <p class="mt-1.5 text-sm text-slate-500">{{ $technique->summary }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-20">
    <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
        <h2 class="text-3xl font-extrabold sm:text-4xl">Your Swimming Journey Starts Here.</h2>
        <p class="mx-auto mt-4 max-w-xl text-slate-500">Create a free account to track lessons, log swims, join challenges and earn badges.</p>
        <div class="mt-8 flex justify-center gap-3">
            <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="btn btn-primary px-6 py-3">{{ auth()->check() ? 'Go to dashboard' : 'Create free account' }}</a>
            <a href="{{ route('safety.index') }}" class="btn btn-outline px-6 py-3">Visit the Water Safety Center</a>
        </div>
    </div>
</section>
@endsection
