<?php

namespace App\Http\Controllers;

use App\Models\ProgramEnrollment;
use App\Models\ProgramExercise;
use App\Models\TrainingProgram;
use App\Services\AchievementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'level', 'duration']);

        $enrolled = $request->user()
            ? $request->user()->enrollments()->get()->keyBy('program_id')
            : collect();

        return view('programs.index', [
            'programs' => TrainingProgram::filter($filters)->withCount('exercises')->ordered()->get(),
            'filters' => $filters,
            'enrolled' => $enrolled,
        ]);
    }

    public function show(Request $request, TrainingProgram $program)
    {
        $program->load('exercises');
        $enrollment = $request->user()?->enrollments()->where('program_id', $program->id)->first();
        $done = $enrollment ? $enrollment->completedIds() : [];
        $total = $program->exercises->count();

        return view('programs.show', [
            'program' => $program,
            'weeks' => $program->exercises->groupBy('week'),
            'enrollment' => $enrollment,
            'done' => $done,
            'percent' => $total ? (int) round(count($done) / $total * 100) : 0,
        ]);
    }

    public function enroll(Request $request, TrainingProgram $program): RedirectResponse
    {
        ProgramEnrollment::firstOrCreate(
            ['user_id' => $request->user()->id, 'program_id' => $program->id],
            ['completed_exercises' => []]
        );

        return back()->with('success', "You are now enrolled in {$program->title}.");
    }

    public function toggle(Request $request, TrainingProgram $program, ProgramExercise $exercise, AchievementService $achievements): RedirectResponse
    {
        abort_unless($exercise->program_id === $program->id, 404);

        $enrollment = ProgramEnrollment::where(['user_id' => $request->user()->id, 'program_id' => $program->id])->firstOrFail();

        $done = $enrollment->completedIds();
        $done = in_array($exercise->id, $done) ? array_values(array_diff($done, [$exercise->id])) : [...$done, $exercise->id];

        $validIds = $program->exercises()->pluck('id')->all();
        $done = array_values(array_intersect($done, $validIds));
        $finished = count($validIds) > 0 && count($done) === count($validIds);

        $enrollment->update([
            'completed_exercises' => $done,
            'completed_at' => $finished ? ($enrollment->completed_at ?? now()) : null,
        ]);

        if ($finished) {
            $request->user()->pushAlert('Program completed', "You finished {$program->title}.", route('programs.show', $program));
            $achievements->sync($request->user());
        }

        return back();
    }
}
