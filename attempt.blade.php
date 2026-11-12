@extends('layouts.dashboard')
@section('title', $attempt->category->name.' quiz')

@section('content')
@php
    $config = ['questions' => $payload, 'startIndex' => $startIndex, 'answerUrl' => route('quiz.answer', $attempt)];
@endphp

<div class="mx-auto max-w-2xl" x-data="quiz(@js($config))">
    <div class="mb-6">
        <a href="{{ route('quiz.index') }}" class="text-sm text-slate-500 hover:text-ocean">Back to quizzes</a>
        <h1 class="mt-2 text-2xl font-extrabold">{{ $attempt->category->name }}</h1>
        <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
            <span>Question <span x-text="index + 1"></span> of <span x-text="questions.length"></span></span>
            <span x-text="progress + '%'"></span>
        </div>
        <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full bg-gradient-to-r from-ocean to-aqua transition-all" :style="'width:' + progress + '%'"></div></div>
    </div>

    <div class="card p-6 sm:p-8">
        <h2 class="text-xl leading-snug" x-text="current.text"></h2>
        <div class="mt-6 space-y-3" role="radiogroup" :aria-label="current.text">
            <template x-for="choice in current.choices" :key="choice.id">
                <button type="button" role="radio" :aria-checked="selected === choice.id" :disabled="feedback !== null"
                        class="flex w-full items-center gap-3 rounded-xl border-2 px-4 py-3 text-left text-sm font-medium text-navy transition" :class="choiceClass(choice)" @click="selected = choice.id">
                    <span class="grid h-5 w-5 shrink-0 place-items-center rounded-full border-2" :class="selected === choice.id ? 'border-ocean bg-ocean' : 'border-slate-300'"><span class="h-1.5 w-1.5 rounded-full bg-white" x-show="selected === choice.id"></span></span>
                    <span x-text="choice.label"></span>
                </button>
            </template>
        </div>

        <p x-show="error" x-cloak class="mt-4 text-sm text-red-600" x-text="error" role="alert"></p>

        <div x-show="feedback" x-cloak class="mt-6 rounded-xl p-4" :class="feedback && feedback.correct ? 'bg-emerald-50 text-emerald-900' : 'bg-red-50 text-red-900'" role="status">
            <p class="font-bold" x-text="feedback && feedback.correct ? 'Correct!' : 'Not quite.'"></p>
            <p class="mt-1 text-sm" x-text="feedback && feedback.explanation"></p>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" class="btn btn-primary" x-show="!feedback" :disabled="!selected || loading" @click="submit()">Submit answer</button>
            <button type="button" class="btn btn-primary" x-show="feedback" x-cloak @click="next()" x-text="isLast ? 'Finish quiz' : 'Next question'"></button>
        </div>
    </div>

    <form x-ref="finish" method="POST" action="{{ route('quiz.finish', $attempt) }}" class="hidden">@csrf</form>
</div>
@endsection
