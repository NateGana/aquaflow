<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizCategory;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizQuestionController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $category = $request->query('category');

        return view('admin.quiz.index', [
            'questions' => QuizQuestion::with('category')->withCount('choices')
                ->when($q !== '', fn ($query) => $query->where('question', 'like', "%{$q}%"))
                ->when($category, fn ($query) => $query->where('quiz_category_id', $category))
                ->latest('id')->paginate(15)->withQueryString(),
            'categories' => QuizCategory::orderBy('id')->get(),
            'q' => $q,
            'category' => $category,
        ]);
    }

    public function create()
    {
        return view('admin.quiz.form', ['question' => null, 'categories' => QuizCategory::orderBy('id')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data) {
            $question = QuizQuestion::create([
                'quiz_category_id' => $data['quiz_category_id'],
                'question' => $data['question'],
                'explanation' => $data['explanation'],
            ]);

            foreach ($data['choices'] as $index => $label) {
                $question->choices()->create(['label' => $label, 'is_correct' => $index === (int) $data['correct']]);
            }
        });

        return redirect()->route('admin.quiz-questions.index')->with('success', 'Question created.');
    }

    public function edit(QuizQuestion $quiz_question)
    {
        return view('admin.quiz.form', ['question' => $quiz_question->load('choices'), 'categories' => QuizCategory::orderBy('id')->get()]);
    }

    public function update(Request $request, QuizQuestion $quiz_question): RedirectResponse
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data, $quiz_question) {
            $quiz_question->update([
                'quiz_category_id' => $data['quiz_category_id'],
                'question' => $data['question'],
                'explanation' => $data['explanation'],
            ]);

            $choices = $quiz_question->choices()->get();

            foreach ($data['choices'] as $index => $label) {
                $attributes = ['label' => $label, 'is_correct' => $index === (int) $data['correct']];
                $choices->get($index) ? $choices->get($index)->update($attributes) : $quiz_question->choices()->create($attributes);
            }
        });

        return redirect()->route('admin.quiz-questions.index')->with('success', 'Question updated.');
    }

    public function destroy(QuizQuestion $quiz_question): RedirectResponse
    {
        $quiz_question->delete();

        return redirect()->route('admin.quiz-questions.index')->with('success', 'Question deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'quiz_category_id' => ['required', 'exists:quiz_categories,id'],
            'question' => ['required', 'string', 'max:1000'],
            'explanation' => ['required', 'string', 'max:1500'],
            'choices' => ['required', 'array', 'size:4'],
            'choices.*' => ['required', 'string', 'max:255'],
            'correct' => ['required', 'integer', 'between:0,3'],
        ]);
    }
}
