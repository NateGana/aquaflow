@extends('layouts.admin')
@section('title', $question ? 'Edit question' : 'New question')

@section('content')
@php
    $choices = $question ? $question->choices->values() : collect();
    $correct = old('correct', $choices->search(fn ($choice) => $choice->is_correct) ?: 0);
@endphp
<x-page-header :title="$question ? 'Edit question' : 'New question'">
    <x-slot:actions><a href="{{ route('admin.quiz-questions.index') }}" class="btn btn-outline">Back to list</a></x-slot:actions>
</x-page-header>

<form method="POST" action="{{ $question ? route('admin.quiz-questions.update', $question) : route('admin.quiz-questions.store') }}" class="card max-w-3xl space-y-5 p-6 sm:p-8">
    @csrf
    @if($question) @method('PUT') @endif
    <div>
        <label for="quiz_category_id" class="label">Category</label>
        <select id="quiz_category_id" name="quiz_category_id" class="input">@foreach($categories as $category)<option value="{{ $category->id }}" @selected((int) old('quiz_category_id', $question?->quiz_category_id) === $category->id)>{{ $category->name }}</option>@endforeach</select>
    </div>
    <div><label for="question" class="label">Question</label><textarea id="question" name="question" rows="3" class="input" required>{{ old('question', $question?->question) }}</textarea></div>

    <fieldset>
        <legend class="label">Answer choices (select the correct one)</legend>
        <div class="space-y-3">
            @foreach(range(0, 3) as $index)
                <div class="flex items-center gap-3">
                    <input type="radio" name="correct" value="{{ $index }}" class="text-ocean focus:ring-aqua" @checked((int) $correct === $index) aria-label="Mark choice {{ $index + 1 }} as correct">
                    <input type="text" name="choices[{{ $index }}]" value="{{ old('choices.'.$index, $choices[$index]->label ?? '') }}" placeholder="Choice {{ $index + 1 }}" class="input" required maxlength="255">
                </div>
            @endforeach
        </div>
    </fieldset>

    <div><label for="explanation" class="label">Explanation shown after answering</label><textarea id="explanation" name="explanation" rows="3" class="input" required>{{ old('explanation', $question?->explanation) }}</textarea></div>

    <div class="flex gap-3"><button class="btn btn-primary">Save question</button><a href="{{ route('admin.quiz-questions.index') }}" class="btn btn-outline">Cancel</a></div>
</form>
@endsection
