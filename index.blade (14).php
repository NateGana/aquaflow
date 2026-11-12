@extends('layouts.dashboard')
@section('title', 'Water Safety Center')

@section('content')
<x-page-header title="Water Safety Center" subtitle="Awareness and habits that keep swimmers safe in pools, at the beach and in open water."/>

<section class="mb-8 overflow-hidden rounded-3xl border-2 border-red-200 bg-red-50 p-6 sm:p-8" aria-labelledby="emergency-title">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-start">
        <span class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-red-600 text-white"><x-icon name="phone" class="h-7 w-7"/></span>
        <div class="flex-1">
            <h2 id="emergency-title" class="text-xl text-red-800">Emergency awareness</h2>
            <p class="mt-2 text-red-900/80">If someone is in trouble in the water, act fast and get trained help.</p>
            <ol class="mt-4 grid gap-3 text-sm text-red-900 sm:grid-cols-3">
                <li class="rounded-xl bg-white/70 p-4"><span class="font-bold">1. Alert a lifeguard.</span> Shout and signal for the nearest lifeguard or staff member right away.</li>
                <li class="rounded-xl bg-white/70 p-4"><span class="font-bold">2. Call your local emergency number.</span> Numbers differ by country, so learn the one for where you swim.</li>
                <li class="rounded-xl bg-white/70 p-4"><span class="font-bold">3. Stay safe.</span> Do not enter the water unless you are trained. Only reach or throw a flotation aid from a safe position if it is safe to do so.</li>
            </ol>
        </div>
    </div>
</section>

<section class="card mb-8 p-6" aria-labelledby="reminders-title">
    <h2 id="reminders-title" class="text-lg">Important safety reminders</h2>
    <ul class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @foreach(['Never swim alone.', 'Always follow lifeguard instructions.', 'Never enter unfamiliar or unsafe water.', 'Avoid swimming during dangerous weather.', 'Do not attempt a rescue beyond your training.', 'Use appropriate flotation equipment when necessary.'] as $reminder)
            <li class="flex items-start gap-3 rounded-xl bg-mist/60 p-4 text-sm font-medium text-navy"><span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-ocean text-white"><x-icon name="shield" class="h-3 w-3"/></span>{{ $reminder }}</li>
        @endforeach
    </ul>
    <p class="mt-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">Online tutorials do not replace certified swimming instruction, lifeguard supervision, or hands-on CPR and rescue training. Please take an in-person course.</p>
</section>

<form method="GET" action="{{ route('safety.index') }}" class="mb-6 flex gap-2">
    <label class="sr-only" for="safety-q">Search safety articles</label>
    <input id="safety-q" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search safety articles..." class="input max-w-md">
    <button class="btn btn-primary">Search</button>
    @if(! empty($filters['q']))<a href="{{ route('safety.index') }}" class="btn btn-outline">Reset</a>@endif
</form>

@if($guides->isEmpty())
    <x-empty-state title="No safety articles found" text="Try different words." icon="search"/>
@else
    <div class="grid gap-4 lg:grid-cols-2">
        @foreach($guides as $guide)
            <article id="{{ $guide->slug }}" class="card scroll-mt-24 p-6" x-data="{ open: false }" x-init="open = window.location.hash === '#{{ $guide->slug }}'">
                <div class="flex items-start gap-4">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-mist text-ocean"><x-icon :name="$loop->index % 3 === 0 ? 'shield' : ($loop->index % 3 === 1 ? 'lifebuoy' : 'alert')" class="h-5 w-5"/></span>
                    <div class="flex-1">
                        <h2 class="text-lg">{{ $guide->title }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $guide->summary }}</p>
                    </div>
                </div>
                <div x-show="open" x-cloak class="mt-4 border-t border-slate-100 pt-4">
                    <p class="text-sm leading-relaxed">{{ $guide->content }}</p>
                    <ul class="list-check mt-4 space-y-2 text-sm">@foreach($guide->points ?? [] as $point)<li>{{ $point }}</li>@endforeach</ul>
                </div>
                <button type="button" class="mt-4 text-sm font-semibold text-ocean hover:underline" @click="open = !open" :aria-expanded="open" x-text="open ? 'Show less' : 'Read more'">Read more</button>
            </article>
        @endforeach
    </div>
@endif
@endsection
