@extends('layouts.dashboard')
@section('title', 'Swimming Techniques')

@section('content')
<x-page-header title="Swimming techniques" subtitle="Detailed guides to body position, arms, legs and breathing for every stroke and core water skill."/>

<x-filter-bar :action="route('techniques.index')" :filters="$filters" placeholder="Search techniques..."/>

@if($techniques->isEmpty())
    <x-empty-state title="No techniques found" text="Try another search or reset the filters." icon="search"/>
@else
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach($techniques as $technique)
            <a href="{{ route('techniques.show', $technique) }}" class="card card-hover overflow-hidden">
                <x-media :src="$technique->imageUrl()" :seed="$technique->id + 1" :label="$technique->title" class="h-36" icon="techniques"/>
                <div class="p-5">
                    <x-level-badge :level="$technique->level"/>
                    <h2 class="mt-3 text-lg">{{ $technique->title }}</h2>
                    <p class="mt-1.5 text-sm text-slate-500">{{ $technique->summary }}</p>
                </div>
            </a>
        @endforeach
    </div>
@endif
@endsection
