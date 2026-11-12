<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ChecksOwnership;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizCategory;
use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use App\Services\AchievementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    use ChecksOwnership;

    public function index(Request $request)
    {
        return view('quiz.index', [
            'categories' => QuizCategory::withCount('questions')->orderBy('id')->get(),
            'history' => $request->user()->quizAttempts()->whereNotNull('completed_at')->with('category')->latest('completed_at')->limit(10)->get(),
        ]);
    }

    public function start(Request $request, QuizCategory $category): RedirectResponse
    {
        $ids = $category->questions()->inRandomOrder()->limit(10)->pluck('id')->all();

        if (count($ids) === 0) {
            return back()->with('error', 'This quiz has no questions yet.');
        }

        $attempt = $request->user()->quizAttempts()->create([
            'quiz_category_id' => $category->id,
            'question_ids' => $ids,
            'total_questions' => count($ids),
        ]);

        return redirect()->route('quiz.attempt', $attempt);
    }

    public function attempt(QuizAttempt $attempt, AchievementService $achievements)
    {
        $this->owned($attempt);

        if ($attempt->completed_at) {
            return redirect()->route('quiz.result', $attempt);
        }

        $answered = $attempt->answers()->count();

        if ($answered >= $attempt->total_questions) {
            $this->complete($attempt, $achievements);

            return redirect()->route('quiz.result', $attempt);
        }

        $order = $attempt->question_ids;
        $questions = QuizQuestion::with('choices')->whereIn('id', $order)->get()->sortBy(fn ($q) => array_search($q->id, $order))->values();

        return view('quiz.attempt', [
            'attempt' => $attempt->load('category'),
            'payload' => $questions->map(fn ($q) => [
                'id' => $q->id,
                'text' => $q->question,
                'choices' => $q->choices->map(fn ($c) => ['id' => $c->id, 'label' => $c->label])->all(),
            ])->all(),
            'startIndex' => $answered,
        ]);
    }

    public function answer(Request $request, QuizAttempt $attempt): JsonResponse
    {
        $this->owned($attempt);
        abort_if($attempt->completed_at, 409, 'This quiz is already finished.');

        $data = $request->validate([
            'question_id' => ['required', 'integer'],
            'choice_id' => ['required', 'integer'],
        ]);

        abort_unless(in_array($data['question_id'], $attempt->question_ids), 422);

        $question = QuizQuestion::with('choices')->findOrFail($data['question_id']);
        $choice = $question->choices->firstWhere('id', $data['choice_id']);
        abort_unless($choice instanceof QuizChoice, 422);

        $answer = QuizAnswer::firstOrCreate(
            ['quiz_attempt_id' => $attempt->id, 'quiz_question_id' => $question->id],
            ['quiz_choice_id' => $choice->id, 'is_correct' => $choice->is_correct]
        );

        return response()->json([
            'correct' => $answer->is_correct,
            'chosen_choice_id' => $answer->quiz_choice_id,
            'correct_choice_id' => $question->correctChoice()?->id,
            'explanation' => $question->explanation,
        ]);
    }

    public function finish(QuizAttempt $attempt, AchievementService $achievements): RedirectResponse
    {
        $this->owned($attempt);
        $this->complete($attempt, $achievements);

        return redirect()->route('quiz.result', $attempt);
    }

    public function result(QuizAttempt $attempt)
    {
        $this->owned($attempt);
        abort_unless($attempt->completed_at, 404);

        $answers = $attempt->answers()->with(['choice', 'question.choices'])->get()->keyBy('quiz_question_id');
        $questions = QuizQuestion::with('choices')->whereIn('id', $attempt->question_ids)->get()
            ->sortBy(fn ($q) => array_search($q->id, $attempt->question_ids))->values();

        return view('quiz.result', [
            'attempt' => $attempt->load('category'),
            'questions' => $questions,
            'answers' => $answers,
        ]);
    }

    private function complete(QuizAttempt $attempt, AchievementService $achievements): void
    {
        if ($attempt->completed_at) {
            return;
        }

        $attempt->update([
            'score' => $attempt->answers()->where('is_correct', true)->count(),
            'completed_at' => now(),
        ]);

        $achievements->sync($attempt->user);
    }
}
