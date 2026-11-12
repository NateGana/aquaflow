<?php

namespace App\Http\Controllers;

use App\Models\SwimmingCategory;
use App\Models\SwimmingLesson;
use App\Models\Technique;
use App\Models\TrainingProgram;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'categories' => SwimmingCategory::withCount('lessons')->orderBy('id')->get(),
            'lessons' => SwimmingLesson::published()->with('category')->withCount(['progress as learners' => fn ($q) => $q->whereNotNull('completed_at')])
                ->orderByDesc('learners')->orderBy('sort_order')->limit(6)->get(),
            'techniques' => Technique::orderBy('id')->limit(6)->get(),
            'counts' => [
                'lessons' => SwimmingLesson::published()->count(),
                'techniques' => Technique::count(),
                'programs' => TrainingProgram::count(),
            ],
        ]);
    }
}
