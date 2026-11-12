<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LessonProgress;
use App\Models\QuizAttempt;
use App\Models\SwimmingLesson;
use App\Models\TrainingProgram;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Registered users', 'value' => User::count(), 'icon' => 'users'],
                ['label' => 'Swimming lessons', 'value' => SwimmingLesson::count(), 'icon' => 'academy'],
                ['label' => 'Training programs', 'value' => TrainingProgram::count(), 'icon' => 'programs'],
                ['label' => 'Completed lessons', 'value' => LessonProgress::whereNotNull('completed_at')->count(), 'icon' => 'check'],
                ['label' => 'Quiz attempts', 'value' => QuizAttempt::whereNotNull('completed_at')->count(), 'icon' => 'quiz'],
            ],
            'recent' => User::with('role')->latest()->limit(8)->get(),
        ]);
    }
}
