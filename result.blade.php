@extends('layouts.dashboard')
@section('title', 'Quiz results')

@section('content')
@php $percent = $attempt->percent(); @endphp
<div class="mx-auto max-w-3xl">
    <div class="card overflow-hidden text-center">
        <div class="bg-gradient-to-br from-navy to-ocean px-6 py-10 text-white">
            <p class="text-sm text-white/70">{{ $attempt->category->name }}</p>
            <p class="mt-3 text-6xl font-extrabold text-white">{{ $attempt->score }}<span class="text-3xl text-white/60">/{{ $attempt->total_questions }}</span></p>
            <p class="mt-2 text-lg font-semibold">{{ $percent }}%</p>
            <p class="mt-3 text-white/80">{{ $percent >= 90 ? 'Outstanding! You really know your swimming.' : ($percent >= 70 ? 'Great work! You have a solid understanding.' : ($percent >= 50 ? 'A good start. Review the explanations below and try again.' : 'Keep learning. The explanations below will help.')) }}</p>
        </div>
        <div class="flex flex-wrap justify-center gap-3 p-6">
            <form method="POST" action="{{ route('quiz.start', $attempt->category) }}">@csrf<button class="btn btn-primary">Try again</button></form>
            <a href="{{ route('quiz.index') }}" class="btn btn-outline">All quizzes</a>
        </div>
    </div>

    <h2 class="mb-4 mt-10 text-xl">Review your answers</h2>
    <div class="space-y-4">
        @foreach($questions as $question)
            @php $answer = $answers->get($question->id); $correct = $answer?->is_correct; @endphp
            <article class="card p-6">
                <div class="flex items-start gap-3">
                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full text-white {{ $correct ? 'bg-emerald-500' : 'bg-red-500' }}"><x-icon :name="$correct ? 'check' : 'x'" class="h-4 w-4"/></span>
                    <h3 class="text-base">{{ $loop->iteration }}. {{ $question->question }}</h3>
                </div>
                <ul class="mt-4 space-y-2 text-sm">
                    @foreach($question->choices as $choice)
                        <li class="rounded-lg border px-3 py-2 {{ $choice->is_correct ? 'border-emerald-300 bg-emerald-50 font-semibold text-emerald-900' : ($answer && $answer->quiz_choice_id === $choice->id ? 'border-red-300 bg-red-50 text-red-900' : 'border-slate-200') }}">
                            {{ $choice->label }}
                            @if($choice->is_correct)<span class="ml-1 text-xs">(correct)</span>@elseif($answer && $answer->quiz_choice_id === $choice->id)<span class="ml-1 text-xs">(your answer)</span>@endif
                        </li>
                    @endforeach
                </ul>
                @unless($answer)<p class="mt-3 text-sm text-amber-700">You did not answer this question.</p>@endunless
                <p class="mt-4 rounded-lg bg-mist/60 px-3 py-2 text-sm text-navy">{{ $question->explanation }}</p>
            </article>
        @endforeach
    </div>
</div>
@endsection
