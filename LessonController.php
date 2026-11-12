<?php

namespace App\Http\Controllers;

use App\Models\LessonProgress;
use App\Models\SwimmingCategory;
use App\Models\SwimmingLesson;
use App\Services\AchievementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'level', 'category', 'duration']);

        $lessons = SwimmingLesson::published()->with('category')->filter($filters)->ordered()->orderBy('sort_order')->get();

        $completed = $request->user()
            ? $request->user()->lessonProgress()->whereNotNull('completed_at')->pluck('lesson_id')->all()
            : [];

        return view('lessons.index', [
            'grouped' => $lessons->groupBy('level'),
            'categories' => SwimmingCategory::orderBy('id')->get(),
            'filters' => $filters,
            'completed' => $completed,
            'total' => $lessons->count(),
        ]);
    }

    public function show(Request $request, SwimmingLesson $lesson)
    {
        abort_unless($lesson->is_published || $request->user()?->isAdmin(), 404);

        $progress = null;

        if ($user = $request->user()) {
            $progress = LessonProgress::firstOrCreate(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                ['started_at' => now()]
            );
        }

        return view('lessons.show', [
            'lesson' => $lesson->load('category'),
            'progress' => $progress,
            'related' => $lesson->related()->load('category'),
        ]);
    }

    public function complete(Request $request, SwimmingLesson $lesson, AchievementService $achievements): RedirectResponse
    {
        $progress = LessonProgress::firstOrNew(['user_id' => $request->user()->id, 'lesson_id' => $lesson->id]);
        $progress->started_at ??= now();
        $progress->completed_at ??= now();
        $progress->save();

        $achievements->sync($request->user());

        return back()->with('success', "Lesson completed: {$lesson->title}");
    }

    public function reopen(Request $request, SwimmingLesson $lesson): RedirectResponse
    {
        LessonProgress::where(['user_id' => $request->user()->id, 'lesson_id' => $lesson->id])->update(['completed_at' => null]);

        return back()->with('success', 'Lesson marked as not completed.');
    }
}
