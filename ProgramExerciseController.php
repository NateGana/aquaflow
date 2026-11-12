<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramExercise;
use App\Models\TrainingProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProgramExerciseController extends Controller
{
    public function store(Request $request, TrainingProgram $program): RedirectResponse
    {
        $program->exercises()->create($this->validated($request));

        return back()->with('success', 'Exercise added.');
    }

    public function update(Request $request, TrainingProgram $program, ProgramExercise $exercise): RedirectResponse
    {
        abort_unless($exercise->program_id === $program->id, 404);
        $exercise->update($this->validated($request));

        return back()->with('success', 'Exercise updated.');
    }

    public function destroy(TrainingProgram $program, ProgramExercise $exercise): RedirectResponse
    {
        abort_unless($exercise->program_id === $program->id, 404);
        $exercise->delete();

        return back()->with('success', 'Exercise removed.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'week' => ['required', 'integer', 'min:1', 'max:52'],
            'day' => ['required', 'integer', 'min:1', 'max:7'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'distance_m' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ]);
    }
}
