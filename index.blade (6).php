@extends('layouts.admin')
@section('title', 'Quiz questions')

@section('content')
<x-page-header title="Quiz questions" subtitle="Manage questions, answer choices and the correct answer for each quiz category.">
    <x-slot:actions><a href="{{ route('admin.quiz-questions.create') }}" class="btn btn-primary"><x-icon name="plus" class="h-4 w-4"/> New question</a></x-slot:actions>
</x-page-header>

<form method="GET" action="{{ route('admin.quiz-questions.index') }}" class="mb-6 grid gap-2 sm:grid-cols-4">
    <input type="search" name="q" value="{{ $q }}" placeholder="Search questions..." class="input sm:col-span-2" aria-label="Search questions">
    <select name="category" class="input" aria-label="Category"><option value="">All categories</option>@foreach($categories as $item)<option value="{{ $item->id }}" @selected((string) $category === (string) $item->id)>{{ $item->name }}</option>@endforeach</select>
    <button class="btn btn-primary">Filter</button>
</form>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="px-6 py-3 font-semibold">Question</th><th class="px-3 py-3 font-semibold">Category</th><th class="px-3 py-3 font-semibold">Choices</th><th class="px-6 py-3"></th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($questions as $question)
                    <tr>
                        <td class="max-w-md truncate px-6 py-3 font-medium text-navy">{{ $question->question }}</td>
                        <td class="px-3 py-3">{{ $question->category->name }}</td>
                        <td class="px-3 py-3">{{ $question->choices_count }}</td>
                        <td class="whitespace-nowrap px-6 py-3 text-right">
                            <a href="{{ route('admin.quiz-questions.edit', $question) }}" class="btn btn-outline btn-sm"><x-icon name="edit" class="h-3.5 w-3.5"/> Edit</a>
                            <form method="POST" action="{{ route('admin.quiz-questions.destroy', $question) }}" class="inline" data-confirm="Delete this question?">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" aria-label="Delete"><x-icon name="trash" class="h-3.5 w-3.5"/></button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500">No questions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-t border-slate-100 px-6 py-4">{{ $questions->links() }}</div>
</div>
@endsection
