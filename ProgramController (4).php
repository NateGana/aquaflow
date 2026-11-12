<?php

namespace App\Http\Controllers\Admin;

use App\Models\TrainingProgram;
use App\Support\Level;
use Illuminate\Database\Eloquent\Model;

class ProgramController extends CrudController
{
    protected function model(): string
    {
        return TrainingProgram::class;
    }

    protected function route(): string
    {
        return 'admin.programs';
    }

    protected function singular(): string
    {
        return 'Training program';
    }

    protected function plural(): string
    {
        return 'Training programs';
    }

    protected function counts(): array
    {
        return ['exercises'];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Program name', 'type' => 'text', 'rules' => 'required|string|max:150'],
            ['name' => 'level', 'label' => 'Difficulty level', 'type' => 'select', 'options' => Level::ALL, 'rules' => 'required|in:beginner,intermediate,advanced'],
            ['name' => 'duration_weeks', 'label' => 'Duration (weeks)', 'type' => 'number', 'rules' => 'required|integer|min:1|max:52'],
            ['name' => 'sessions_per_week', 'label' => 'Sessions per week', 'type' => 'number', 'rules' => 'required|integer|min:1|max:14'],
            ['name' => 'summary', 'label' => 'Short description', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'objectives', 'label' => 'Training objectives (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:5000'],
            ['name' => 'equipment', 'label' => 'Required equipment (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:2000'],
            ['name' => 'image', 'label' => 'Image (JPG, PNG or WebP, max 2 MB)', 'type' => 'file', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
        ];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Program'],
            ['key' => 'level', 'label' => 'Level', 'badge' => 'level'],
            ['key' => 'duration_weeks', 'label' => 'Weeks'],
            ['key' => 'exercises_count', 'label' => 'Exercises'],
        ];
    }

    protected function extra(?Model $item): ?string
    {
        return $item ? 'admin.programs.exercises' : null;
    }

    protected function extraData(?Model $item): array
    {
        return $item ? ['program' => $item->load('exercises')] : [];
    }
}
