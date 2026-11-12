@extends('layouts.dashboard')
@section('title', 'Swimming Quiz')

@section('content')
<x-page-header title="Swimming knowledge quiz" subtitle="Test what you know. Each question gives instant feedback and an explanation, and your results are saved."/>

<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    @foreach($categories as $category)
        <article class="card flex flex-col p-6">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-mist text-ocean"><x-icon name="quiz" class="h-5 w-5"/></span>
            <h2 class="mt-4 text-lg">{{ $category->name }}</h2>
            <p class="mt-1.5 flex-1 text-sm text-slate-500">{{ $category->description }}</p>
            <p class="mt-3 text-xs text-slate-400">{{ $category->questions_count }} questions</p>
            <form method="POST" action="{{ route('quiz.start', $category) }}" class="mt-4">@csrf
                <button class="btn btn-primary w-full" @disabled($category->questions_count === 0)>Start quiz</button>
            </form>
        </article>
    @endforeach
</div>

<section class="mt-10 card overflow-hidden">
    <div class="border-b border-slate-100 px-6 py-4"><h2 class="text-lg">Quiz history</h2></div>
    @if($history->isEmpty())
        <p class="px-6 py-10 text-center text-sm text-slate-500">You have not finished a quiz yet.</p>
    @else
        <ul class="divide-y divide-slate-100">
            @foreach($history as $attempt)
                <li class="flex flex-wrap items-center justify-between gap-3 px-6 py-4">
                    <div>
                        <p class="font-semibold text-navy">{{ $attempt->category->name }}</p>
                        <p class="text-xs text-slate-500">{{ $attempt->completed_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="badge {{ $attempt->percent() >= 70 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $attempt->score }}/{{ $attempt->total_questions }} &middot; {{ $attempt->percent() }}%</span>
                        <a href="{{ route('quiz.result', $attempt) }}" class="text-sm font-semibold text-ocean hover:underline">Review</a>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</section>
@endsection
