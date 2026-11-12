@extends('layouts.dashboard')
@section('title', 'Search')

@section('content')
@php
    $linkFor = fn ($group, $item) => match ($group) {
        'Lessons' => route('lessons.show', $item),
        'Techniques' => route('techniques.show', $item),
        'Training programs' => route('programs.show', $item),
        'Equipment guides' => route('equipment.index').'#'.$item->slug,
        default => route('safety.index').'#'.$item->slug,
    };
@endphp

<x-page-header title="Search" :subtitle="$searching ? $count.' '.\Illuminate\Support\Str::plural('result', $count).' for “'.$term.'”' : 'Search lessons, techniques, programs, equipment and safety articles.'"/>

<form method="GET" action="{{ route('search') }}" class="card mb-8 grid gap-3 p-4 sm:grid-cols-4">
    <input type="search" name="q" value="{{ $term }}" placeholder="Type at least 2 characters..." class="input sm:col-span-2" aria-label="Search" autofocus>
    <select name="level" class="input" aria-label="Difficulty"><option value="">All levels</option>@foreach(\App\Support\Level::ALL as $key => $label)<option value="{{ $key }}" @selected($level === $key)>{{ $label }}</option>@endforeach</select>
    <button class="btn btn-primary">Search</button>
</form>

@if($searching && $count === 0)
    <x-empty-state title="Nothing found" text="Try different words or remove the level filter." icon="search"/>
@endif

@foreach($results as $group => $items)
    @if($items->isNotEmpty())
        <section class="mb-8">
            <h2 class="mb-3 text-lg">{{ $group }}</h2>
            <ul class="card divide-y divide-slate-100">
                @foreach($items as $item)
                    <li>
                        <a href="{{ $linkFor($group, $item) }}" class="flex items-center justify-between gap-4 px-5 py-4 hover:bg-slate-50">
                            <span>
                                <span class="block font-semibold text-navy">{{ $item->title ?? $item->name }}</span>
                                <span class="block text-sm text-slate-500">{{ $item->summary ?? $item->description ?? $item->purpose }}</span>
                            </span>
                            @if(isset($item->level))<x-level-badge :level="$item->level"/>@endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
@endforeach
@endsection
