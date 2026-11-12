@extends('layouts.dashboard')
@section('title', 'Equipment Guide')

@section('content')
<x-page-header title="Equipment guide" subtitle="What each piece of swimming gear does, who it suits and how to use it well. This is an educational guide, not a shop."/>

<form method="GET" action="{{ route('equipment.index') }}" class="mb-8 flex gap-2">
    <label class="sr-only" for="equipment-q">Search equipment</label>
    <input id="equipment-q" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search equipment..." class="input max-w-md">
    <button class="btn btn-primary">Search</button>
    @if(! empty($filters['q']))<a href="{{ route('equipment.index') }}" class="btn btn-outline">Reset</a>@endif
</form>

@if($items->isEmpty())
    <x-empty-state title="No equipment found" text="Try different words." icon="search"/>
@else
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach($items as $item)
            <article id="{{ $item->slug }}" class="card flex scroll-mt-24 flex-col overflow-hidden">
                <x-media :src="$item->imageUrl()" :seed="$item->id" :label="$item->name" class="h-40" icon="equipment"/>
                <div class="flex flex-1 flex-col p-6">
                    <h2 class="text-lg">{{ $item->name }}</h2>
                    <p class="mt-1.5 text-sm text-slate-500">{{ $item->description }}</p>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div><dt class="font-semibold text-navy">Purpose</dt><dd class="text-slate-600">{{ $item->purpose }}</dd></div>
                        <div><dt class="font-semibold text-navy">Benefits</dt><dd class="text-slate-600">{{ $item->benefits }}</dd></div>
                        <div><dt class="font-semibold text-navy">Recommended for</dt><dd class="text-slate-600">{{ $item->recommended_for }}</dd></div>
                    </dl>
                    <div class="mt-4 rounded-xl bg-mist/60 p-4">
                        <p class="text-sm font-semibold text-navy">Proper usage tips</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-slate-600">@foreach($item->usage_tips ?? [] as $tip)<li>{{ $tip }}</li>@endforeach</ul>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@endif
@endsection
